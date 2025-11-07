<?php

namespace View;

use Controller\SqlController;
use Controller\AuthorizationController;

// Alleen beheerders en medewerkers mogen deze pagina zien
AuthorizationController::authorize(['Beheerder', 'Medewerker']);

class RegisterView extends Framework\AuthenticatedLayout
{
    function content()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $errors = [];
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $accountType = $_POST['account_type'] ?? 'Klant';
            $phoneNumber = $_POST['telefoonnummer'] ?? '';
            $contactPerson = $_POST['contactpersoon'] ?? '';

            if (empty($email)) {
                $errors[] = __('email_is_vereist');
            }

            if (empty($password)) {
                $errors[] = __('wachtwoord_is_vereist');
            }

            if ($password !== $confirmPassword) {
                $errors[] = __('wachtwoorden_komen_niet_overeen');
            }

            if (empty($phoneNumber)) {
                $errors[] = __('telefoonnummer_is_vereist');
            }

            if (empty($contactPerson)) {
                $errors[] = __('contactpersoon_is_vereist');
            }

            if (empty($errors)) {
                $sqlController = SqlController::setup();
                $pdo = $this->getPDOInstance($sqlController);
                
                try {
                    $pdo->beginTransaction();

                    $checkEmailSql = 'SELECT emailId FROM Emails WHERE email = :email';
                    $checkEmailStmt = $pdo->prepare($checkEmailSql);
                    $checkEmailStmt->bindValue(':email', $email, \PDO::PARAM_STR);
                    $checkEmailStmt->execute();
                    $checkEmailResult = $checkEmailStmt->fetch(\PDO::FETCH_ASSOC);
                    
                    if ($checkEmailResult) {
                        throw new \Exception(__('email_already_exists'));
                    }

                    $emailSql = 'INSERT INTO Emails (email) VALUES (:email)';
                    $emailStmt = $pdo->prepare($emailSql);
                    $emailStmt->bindValue(':email', $email, \PDO::PARAM_STR);
                    $emailResult = $emailStmt->execute();

                    if (!$emailResult) {
                        $errorInfo = $pdo->errorInfo();
                        throw new \Exception('Failed to insert email. Error Info: ' . json_encode($errorInfo));
                    }

                    $lastEmailId = $pdo->lastInsertId();

                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $accountSql = 'INSERT INTO Accounts (emailId, wachtwoord, accounttype) VALUES (:emailId, :wachtwoord, :accounttype)';
                    $accountStmt = $pdo->prepare($accountSql);
                    $accountStmt->bindValue(':emailId', $lastEmailId, \PDO::PARAM_INT);
                    $accountStmt->bindValue(':wachtwoord', $hashedPassword, \PDO::PARAM_STR);
                    $accountStmt->bindValue(':accounttype', $accountType, \PDO::PARAM_STR);
                    $accountResult = $accountStmt->execute();

                    if (!$accountResult) {
                        $errorInfo = $pdo->errorInfo();
                        throw new \Exception('Failed to insert account. Error Info: ' . json_encode($errorInfo));
                    }

                    $lastAccountId = $pdo->lastInsertId();

                    $contactSql = 'INSERT INTO Contacten (telefoonnummer, contactpersoon) VALUES (:telefoonnummer, :contactpersoon)';
                    $contactStmt = $pdo->prepare($contactSql);
                    $contactStmt->bindValue(':telefoonnummer', $phoneNumber, \PDO::PARAM_STR);
                    $contactStmt->bindValue(':contactpersoon', $contactPerson, \PDO::PARAM_STR);
                    $contactResult = $contactStmt->execute();

                    if (!$contactResult) {
                        $errorInfo = $pdo->errorInfo();
                        throw new \Exception('Failed to insert contact. Error Info: ' . json_encode($errorInfo));
                    }
                  
                    $pdo->commit();
                    $success = true;

                } catch (\Exception $e) {
                    $pdo->rollBack();
                    error_log('Error in registerAccount: ' . $e->getMessage());
                    $errors[] = $e->getMessage();
                }
            }
        }

        ?>
        <div class="login-container">
            <h2><?= __('account_aanmaken'); ?></h2>
            <?php if ($success): ?>
                <p class="success-message"><?= __('account_aangemaakt'); ?></p>
                <a href="/inloggen"><?= __('hier_inloggen'); ?></a>
            <?php else: ?>
                <?php if (!empty($errors)): ?>
                    <div class="error">
                        <?php foreach ($errors as $error): ?>
                            <p><?= htmlspecialchars($error); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="/registreer">
                    <div>
                        <label for="email"><?= __('email'); ?>:</label>
                        <input type="email" id="email" name="email" required placeholder="<?= __('voer_uw_emailadres_in'); ?>">
                    </div>
                    <div>
                        <label for="password"><?= __('wachtwoord'); ?>:</label>
                        <input type="password" id="password" name="password" required placeholder="<?= __('voer_uw_wachtwoord_in'); ?>">
                    </div>
                    <div>
                        <label for="confirm_password"><?= __('wachtwoord_bevestigen'); ?>:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required placeholder="<?= __('bevestig_uw_wachtwoord'); ?>">
                    </div>
                    <div>
                        <label for="account_type"><?= __('account_type'); ?>:</label>
                        <select id="account_type" name="account_type" required>
                            <option value="Klant"><?= __('klant'); ?></option>
                            <option value="Beheerder"><?= __('beheerder'); ?></option>
                            <option value="Medewerker"><?= __('medewerker'); ?></option>
                        </select>
                    </div>
                    <div>
                        <label for="telefoonnummer"><?= __('telefoonnummer'); ?>:</label>
                        <input type="text" id="telefoonnummer" name="telefoonnummer" required placeholder="<?= __('voer_uw_telefoonnummer_in'); ?>">
                    </div>
                    <div>
                        <label for="contactpersoon"><?= __('contactpersoon'); ?>:</label>
                        <input type="text" id="contactpersoon" name="contactpersoon" required placeholder="<?= __('voer_uw_contactpersoon_in'); ?>">
                    </div>
                    <button type="submit"><?= __('account_aanmaken'); ?></button>
                </form>
            <?php endif; ?>
            <a href="/welkom" class="back-button"><?= __('terug_naar_welkom'); ?></a>
        </div>
        <?php
    }

    private function getPDOInstance($sqlController) {
        $reflection = new \ReflectionClass($sqlController);
        $pdoProperty = $reflection->getProperty('pdo');
        $pdoProperty->setAccessible(true);
        return $pdoProperty->getValue($sqlController);
    }
}

new RegisterView();
