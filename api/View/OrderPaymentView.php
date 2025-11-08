<?php

namespace View;

use Controller\SqlController;

ob_start();

class OrderPaymentView extends Framework\AuthenticatedLayout
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

        if (!isset($_GET['order_id'])) {
            echo '<div class="error-message">' . __('geen_order_gevonden') . '</div>';
            return;
        }

        $orderId = $_GET['order_id'];
        $userId = $_SESSION['user_id'];
        $sqlController = SqlController::setup();

        $orderSql = 'SELECT o.*, a.vestigingsnaam, a.postcode, a.huisnummer, a.toevoeging 
                     FROM Orders o 
                     JOIN Adressen a ON o.adresId = a.adresId 
                     WHERE o.orderId = :orderId AND EXISTS (
                         SELECT 1 FROM Klanten k 
                         JOIN Accounts ac ON k.accountId = ac.accountId 
                         WHERE k.klantId = a.klantId AND ac.accountId = :accountId)';
        $orderParams = ['orderId' => $orderId, 'accountId' => $userId];
        $order = $sqlController->sql($orderSql)->params($orderParams)->run();

        if (!$order) {
            echo '<div class="error-message">' . __('geen_order_gevonden') . '</div>';
            return;
        }

        $order = $order[0];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_payment'])) {
            $payOrderSql = 'UPDATE Orders SET betaald = TRUE WHERE orderId = :orderId';
            $payOrderParams = ['orderId' => $orderId];
            $sqlController->sql($payOrderSql)->params($payOrderParams)->run(false);

            header('Location: /order');
            exit;
        }
        ?>
        <div class="order-payment-container">
            <h2><?= __('betaling_order') . ' ' . htmlspecialchars($order['orderId']); ?></h2>
            <div class="order-details">
                <p><strong><?= __('order_id'); ?>:</strong> <?= htmlspecialchars($order['orderId']); ?></p>
                <p><strong><?= __('order_datum'); ?>:</strong> <?= htmlspecialchars($order['orderdatum']); ?></p>
                <p><strong><?= __('totale_prijs'); ?>:</strong> €<?= htmlspecialchars($order['totaalprijs']); ?></p>
                <p><strong><?= __('adres'); ?>:</strong> <?= htmlspecialchars($order['vestigingsnaam']) . ', ' . htmlspecialchars($order['postcode']) . ' ' . htmlspecialchars($order['huisnummer']) . ' ' . htmlspecialchars($order['toevoeging']); ?></p>
            </div>
            <form method="post" action="">
                <label for="payment_method"><?= __('betaalmethode'); ?>:</label>
                <select name="payment_method" id="payment_method">
                    <option value="creditcard">Creditcard</option>
                    <option value="ideal">iDeal</option>
                </select>
                <button type="submit" name="complete_payment"><?= __('voltooi_betaling'); ?></button>
            </form>
            <div class="actions">
                <a href="/order"><?= __('mijn_bestellingen'); ?></a>
            </div>
        </div>
        <?php
    ob_end_flush();
    }
}

new OrderPaymentView();
