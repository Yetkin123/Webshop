<?php

namespace View;

use Controller\SqlController;
use View\Framework\AuthenticatedLayout;

class ReturnView extends AuthenticatedLayout
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

        $klantIdSql = 'SELECT klantId FROM Klanten WHERE accountId = :userId';
        $klantIdStmt = $pdo->prepare($klantIdSql);
        $klantIdStmt->bindValue(':userId', $userId, \PDO::PARAM_INT);
        $klantIdStmt->execute();
        $klantIdResult = $klantIdStmt->fetch(\PDO::FETCH_ASSOC);

        if (!$klantIdResult) {
            $errors[] = __('geen_klant_gevonden');
        } else {
            $klantId = $klantIdResult['klantId'];

            $ordersSql = 'SELECT o.orderId, o.orderdatum, o.totaalprijs
                          FROM Orders o
                          JOIN Adressen a ON o.adresId = a.adresId
                          WHERE a.klantId = :klantId';
            $ordersStmt = $pdo->prepare($ordersSql);
            $ordersStmt->bindValue(':klantId', $klantId, \PDO::PARAM_INT);
            $ordersStmt->execute();
            $orders = $ordersStmt->fetchAll(\PDO::FETCH_ASSOC);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $orderId = $_POST['order_id'] ?? '';

                if (empty($orderId)) {
                    $errors[] = __('order_is_vereist');
                } else {
                    $checkReturnSql = 'SELECT COUNT(*) as count FROM Retouren r
                                       JOIN Orderregels orr ON r.orderregelId = orr.orderregelId
                                       WHERE orr.orderId = :orderId';
                    $checkReturnStmt = $pdo->prepare($checkReturnSql);
                    $checkReturnStmt->bindValue(':orderId', $orderId, \PDO::PARAM_INT);
                    $checkReturnStmt->execute();
                    $returnCount = $checkReturnStmt->fetch(\PDO::FETCH_ASSOC)['count'];

                    if ($returnCount > 0) {
                        $errors[] = __('order_al_reeds_geretourneerd');
                    }
                }

                if (empty($errors)) {
                    try {
                        $pdo->beginTransaction();

                        $insertReturnSql = 'INSERT INTO Retouren (orderregelId, aanvraagdatum, status) 
                                            SELECT orr.orderregelId, NOW(), "Pending" 
                                            FROM Orderregels orr 
                                            WHERE orr.orderId = :orderId';
                        $insertReturnStmt = $pdo->prepare($insertReturnSql);
                        $insertReturnStmt->bindValue(':orderId', $orderId, \PDO::PARAM_INT);
                        $insertReturnStmt->execute();

                        $pdo->commit();

                        $success = true;
                    } catch (\Exception $e) {
                        $pdo->rollBack();
                        error_log('Error in returnRequest: ' . $e->getMessage());
                        $errors[] = $e->getMessage();
                    }
                }
            }
        }

        ?>
        <div class="returns-container">
            <h2><?= __('retour_aanvragen'); ?></h2>
            <?php if ($success): ?>
                <p class="success-message"><?= __('retour_aangevraagd'); ?></p>
            <?php else: ?>
                <?php if (!empty($errors)): ?>
                    <div class="error">
                        <?php foreach ($errors as $error): ?>
                            <p><?= htmlspecialchars($error); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="/retouneren">
                    <label for="order_id"><?= __('selecteer_order_voor_retour'); ?>:</label>
                    <select name="order_id" id="order_id" required>
                        <?php foreach ($orders as $order): ?>
                            <option value="<?= htmlspecialchars($order['orderId']); ?>">
                                <?= __('order _id'); ?>: <?= htmlspecialchars($order['orderId']); ?> - <?= htmlspecialchars($order['orderdatum']); ?> - €<?= htmlspecialchars($order['totaalprijs']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit"><?= __('retour_aanvragen'); ?></button>
                </form>
            <?php endif; ?>
            <a href="/account" class="button"><?= __('terug_naar_account'); ?></a>
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

new ReturnView();
?>
