<?php

namespace View;

use Controller\SqlController;
use View\Framework\AuthenticatedLayout;

class AccountView extends AuthenticatedLayout
{
    function content()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: /inloggen');
            exit;
        }

        $errors = [];
        $success = false;
        $userId = $_SESSION['user_id'];

        $sqlController = SqlController::setup();
        $pdo = $this->getPDOInstance($sqlController);

        $userSql = 'SELECT e.email, a.accountId FROM Emails e JOIN Accounts a ON e.emailId = a.emailId WHERE a.accountId = :userId';
        $userStmt = $pdo->prepare($userSql);
        $userStmt->bindValue(':userId', $userId, \PDO::PARAM_INT);
        $userStmt->execute();
        $userData = $userStmt->fetch(\PDO::FETCH_ASSOC);

        if (!$userData) {
            $errors[] = __('geen_producten_gevonden');
        }

        $addressSql = 'SELECT a.vestigingsnaam, a.postcode, a.huisnummer, a.toevoeging, k.kvkNummer, k.klantId 
                       FROM Adressen a 
                       JOIN Klanten k ON a.klantId = k.klantId 
                       WHERE k.accountId = :userId';
        $addressStmt = $pdo->prepare($addressSql);
        $addressStmt->bindValue(':userId', $userId, \PDO::PARAM_INT);
        $addressStmt->execute();
        $addressData = $addressStmt->fetch(\PDO::FETCH_ASSOC);

        if (!$addressData) {
            $addressData = [
                'vestigingsnaam' => '',
                'postcode' => '',
                'huisnummer' => '',
                'toevoeging' => '',
                'kvkNummer' => '',
                'klantId' => null
            ];

            $klantSql = 'SELECT klantId FROM Klanten WHERE accountId = :userId';
            $klantStmt = $pdo->prepare($klantSql);
            $klantStmt->bindValue(':userId', $userId, \PDO::PARAM_INT);
            $klantStmt->execute();
            $klantData = $klantStmt->fetch(\PDO::FETCH_ASSOC);
            if ($klantData) {
                $addressData['klantId'] = $klantData['klantId'];
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $vestigingsnaam = $_POST['vestigingsnaam'] ?? '';
            $postcode = $_POST['postcode'] ?? '';
            $huisnummer = $_POST['huisnummer'] ?? '';
            $toevoeging = $_POST['toevoeging'] ?? '';
            $kvkNummer = $_POST['kvkNummer'] ?? '';

            if (empty($email)) {
                $errors[] = __('email_is_vereist');
            }

            if (!empty($password) && $password !== $confirmPassword) {
                $errors[] = __('wachtwoorden_komen_niet_overeen');
            }

            if (empty($vestigingsnaam) || empty($postcode) || empty($huisnummer) || empty($kvkNummer)) {
                $errors[] = __('geen_adres');
            }

            if (empty($errors)) {
                try {
                    $pdo->beginTransaction();

                    if ($email !== $userData['email']) {
                        $checkEmailSql = 'SELECT emailId FROM Emails WHERE email = :email';
                        $checkEmailStmt = $pdo->prepare($checkEmailSql);
                        $checkEmailStmt->bindValue(':email', $email, \PDO::PARAM_STR);
                        $checkEmailStmt->execute();
                        $checkEmailResult = $checkEmailStmt->fetch(\PDO::FETCH_ASSOC);

                        if ($checkEmailResult) {
                            throw new \Exception(__('email_already_exists'));
                        }

                        $updateEmailSql = 'UPDATE Emails SET email = :email WHERE emailId = (SELECT emailId FROM Accounts WHERE accountId = :userId)';
                        $updateEmailStmt = $pdo->prepare($updateEmailSql);
                        $updateEmailStmt->bindValue(':email', $email, \PDO::PARAM_STR);
                        $updateEmailStmt->bindValue(':userId', $userId, \PDO::PARAM_INT);
                        $updateEmailStmt->execute();
                    }

                    if (!empty($password)) {
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                        $updatePasswordSql = 'UPDATE Accounts SET wachtwoord = :wachtwoord WHERE accountId = :userId';
                        $updatePasswordStmt = $pdo->prepare($updatePasswordSql);
                        $updatePasswordStmt->bindValue(':wachtwoord', $hashedPassword, \PDO::PARAM_STR);
                        $updatePasswordStmt->bindValue(':userId', $userId, \PDO::PARAM_INT);
                        $updatePasswordStmt->execute();
                    }

                    if (empty($addressData['klantId'])) {
                        $insertKlantSql = 'INSERT INTO Klanten (accountId, kvkNummer) VALUES (:accountId, :kvkNummer)';
                        $insertKlantStmt = $pdo->prepare($insertKlantSql);
                        $insertKlantStmt->bindValue(':accountId', $userId, \PDO::PARAM_INT);
                        $insertKlantStmt->bindValue(':kvkNummer', $kvkNummer, \PDO::PARAM_INT);
                        $insertKlantStmt->execute();

                        $addressData['klantId'] = $pdo->lastInsertId();
                    } else {
                        $updateKvkSql = 'UPDATE Klanten SET kvkNummer = :kvkNummer WHERE klantId = :klantId';
                        $updateKvkStmt = $pdo->prepare($updateKvkSql);
                        $updateKvkStmt->bindValue(':kvkNummer', $kvkNummer, \PDO::PARAM_INT);
                        $updateKvkStmt->bindValue(':klantId', $addressData['klantId'], \PDO::PARAM_INT);
                        $updateKvkStmt->execute();
                    }

                    if ($addressData['klantId']) {
                        $checkAddressSql = 'SELECT adresId FROM Adressen WHERE klantId = :klantId';
                        $checkAddressStmt = $pdo->prepare($checkAddressSql);
                        $checkAddressStmt->bindValue(':klantId', $addressData['klantId'], \PDO::PARAM_INT);
                        $checkAddressStmt->execute();
                        $existingAddress = $checkAddressStmt->fetch(\PDO::FETCH_ASSOC);

                        if ($existingAddress) {
                            $updateAddressSql = 'UPDATE Adressen SET vestigingsnaam = :vestigingsnaam, postcode = :postcode, huisnummer = :huisnummer, toevoeging = :toevoeging WHERE klantId = :klantId';
                            $updateAddressStmt = $pdo->prepare($updateAddressSql);
                            $updateAddressStmt->bindValue(':vestigingsnaam', $vestigingsnaam, \PDO::PARAM_STR);
                            $updateAddressStmt->bindValue(':postcode', $postcode, \PDO::PARAM_STR);
                            $updateAddressStmt->bindValue(':huisnummer', $huisnummer, \PDO::PARAM_INT);
                            $updateAddressStmt->bindValue(':toevoeging', $toevoeging, \PDO::PARAM_STR);
                            $updateAddressStmt->bindValue(':klantId', $addressData['klantId'], \PDO::PARAM_INT);
                            $updateAddressStmt->execute();
                        } else {
                            $insertAddressSql = 'INSERT INTO Adressen (vestigingsnaam, postcode, huisnummer, toevoeging, klantId) VALUES (:vestigingsnaam, :postcode, :huisnummer, :toevoeging, :klantId)';
                            $insertAddressStmt = $pdo->prepare($insertAddressSql);
                            $insertAddressStmt->bindValue(':vestigingsnaam', $vestigingsnaam, \PDO::PARAM_STR);
                            $insertAddressStmt->bindValue(':postcode', $postcode, \PDO::PARAM_STR);
                            $insertAddressStmt->bindValue(':huisnummer', $huisnummer, \PDO::PARAM_INT);
                            $insertAddressStmt->bindValue(':toevoeging', $toevoeging, \PDO::PARAM_STR);
                            $insertAddressStmt->bindValue(':klantId', $addressData['klantId'], \PDO::PARAM_INT);
                            $insertAddressStmt->execute();
                        }
                    } else {
                        throw new \Exception(__('geen_adres'));
                    }

                    $pdo->commit();

                    $success = true;

                } catch (\Exception $e) {
                    $pdo->rollBack();
                    error_log('Error in updateAccount: ' . $e->getMessage());
                    $errors[] = $e->getMessage();
                }
            }
        }

        $ordersSql = 'SELECT o.orderId, o.orderdatum, o.totaalprijs, a.vestigingsnaam, a.postcode, a.huisnummer, a.toevoeging
                      FROM Orders o
                      JOIN Adressen a ON o.adresId = a.adresId
                      WHERE EXISTS (
                          SELECT 1 FROM Klanten k 
                          JOIN Accounts ac ON k.accountId = ac.accountId 
                          WHERE k.klantId = a.klantId AND ac.accountId = :userId)';
        $ordersStmt = $pdo->prepare($ordersSql);
        $ordersStmt->bindValue(':userId', $userId, \PDO::PARAM_INT);
        $ordersStmt->execute();
        $orders = $ordersStmt->fetchAll(\PDO::FETCH_ASSOC);

        $returnsSql = 'SELECT r.retourId, r.aanvraagdatum, r.status, o.orderId
                       FROM Retouren r
                       JOIN Orderregels orr ON r.orderregelId = orr.orderregelId
                       JOIN Orders o ON orr.orderId = o.orderId
                       WHERE EXISTS (
                           SELECT 1 FROM Klanten k 
                           JOIN Accounts ac ON k.accountId = ac.accountId 
                           WHERE k.klantId = (SELECT klantId FROM Adressen WHERE adresId = o.adresId) AND ac.accountId = :userId)';
        $returnsStmt = $pdo->prepare($returnsSql);
        $returnsStmt->bindValue(':userId', $userId, \PDO::PARAM_INT);
        $returnsStmt->execute();
        $returns = $returnsStmt->fetchAll(\PDO::FETCH_ASSOC);

        ?>
        <div class="account-returns-container">
            <div class="account-container">
                <h2><?= __('update_account'); ?></h2>
                <?php if ($success): ?>
                    <p><?= __('account_geupdate'); ?></p>
                <?php else: ?>
                    <?php if (!empty($errors)): ?>
                        <div class="error">
                            <?php foreach ($errors as $error): ?>
                                <p><?= htmlspecialchars($error); ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <form method="post" action="/account">
                        <div>
                            <label for="email"><?= __('email'); ?>:</label>
                            <input type="email" id="email" name="email" required placeholder="<?= __('voer_uw_emailadres_in'); ?>" value="<?= htmlspecialchars($userData['email'] ?? ''); ?>">
                        </div>
                        <div>
                            <label for="password"><?= __('wachtwoord'); ?>:</label>
                            <input type="password" id="password" name="password" placeholder="<?= __('voer_uw_wachtwoord_in'); ?>">
                        </div>
                        <div>
                            <label for="confirm_password"><?= __('wachtwoord_bevestigen'); ?>:</label>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="<?= __('bevestig_uw_wachtwoord'); ?>">
                        </div>
                        <div>
                            <label for="vestigingsnaam"><?= __('vestigingsnaam'); ?>:</label>
                            <input type="text" id="vestigingsnaam" name="vestigingsnaam" required placeholder="<?= __('vestigingsnaam'); ?>" value="<?= htmlspecialchars($addressData['vestigingsnaam'] ?? ''); ?>">
                        </div>
                        <div>
                            <label for="postcode"><?= __('postcode'); ?>:</label>
                            <input type="text" id="postcode" name="postcode" required placeholder="<?= __('postcode'); ?>" value="<?= htmlspecialchars($addressData['postcode'] ?? ''); ?>">
                        </div>
                        <div>
                            <label for="huisnummer"><?= __('huisnummer'); ?>:</label>
                            <input type="text" id="huisnummer" name="huisnummer" required placeholder="<?= __('huisnummer'); ?>" value="<?= htmlspecialchars($addressData['huisnummer'] ?? ''); ?>">
                        </div>
                        <div>
                            <label for="toevoeging"><?= __('toevoeging'); ?>:</label>
                            <input type="text" id="toevoeging" name="toevoeging" placeholder="<?= __('toevoeging'); ?>" value="<?= htmlspecialchars($addressData['toevoeging'] ?? ''); ?>">
                        </div>
                        <div>
                            <label for="kvkNummer"><?= __('kvk_nummer'); ?>:</label>
                            <input type="text" id="kvkNummer" name="kvkNummer" required placeholder="<?= __('kvk_nummer'); ?>" value="<?= htmlspecialchars($addressData['kvkNummer'] ?? ''); ?>">
                        </div>
                        <button type="submit"><?= __('bevestig_update'); ?></button>
                    </form>
                <?php endif; ?>
                <a href="/welkom" class="button"><?= __('terug_naar_welkom'); ?></a>
            </div>

            <div class="returns-container">
                <h2><?= __('uw_bestellingen'); ?></h2>
                <form method="post" action="/retouneren">
                    <label for="order_id"><?= __('selecteer_order_voor_retour'); ?>:</label>
                    <select name="order_id" id="order_id">
                        <?php foreach ($orders as $order): ?>
                            <option value="<?= htmlspecialchars($order['orderId']); ?>">
                                <?= __('order_id'); ?>: <?= htmlspecialchars($order['orderId']); ?> - <?= htmlspecialchars($order['orderdatum']); ?> - €<?= htmlspecialchars($order['totaalprijs']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit"><?= __('retour_aanvragen'); ?></button>
                </form>
                <?php if (!empty($returns)): ?>
                    <div class="returns-overview">
                        <h3><?= __('retour_overzicht'); ?></h3>
                        <table>
                            <thead>
                                <tr>
                                    <th><?= __('retour_id'); ?></th>
                                    <th><?= __('order_datum'); ?></th>
                                    <th><?= __('status'); ?></th>
                                    <th><?= __('order_id'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($returns as $return): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($return['retourId']); ?></td>
                                        <td><?= htmlspecialchars($return['aanvraagdatum']); ?></td>
                                        <td><?= htmlspecialchars($return['status']); ?></td>
                                        <td><?= htmlspecialchars($return['orderId']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p><?= __('geen_retouren_gevonden'); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    private function getPDOInstance($sqlController) {
        // Use the PDO instance from the SqlController
        $reflection = new \ReflectionClass($sqlController);
        $pdoProperty = $reflection->getProperty('pdo');
        $pdoProperty->setAccessible(true);
        return $pdoProperty->getValue($sqlController);
    }
}

new AccountView();
?>
