<?php

namespace View;

use Controller\SqlController;

class OrderConfirmationView extends Framework\AuthenticatedLayout
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

        if (!isset($_SESSION['last_order_id'])) {
            echo '<div class="error-message">' . __("geen_order_gevonden") . '</div>';
            return;
        }

        $orderId = $_SESSION['last_order_id'];
        unset($_SESSION['last_order_id']);

        $sqlController = SqlController::setup();

        $orderSql = 'SELECT o.*, a.vestigingsnaam, a.postcode, a.huisnummer, a.toevoeging 
                     FROM Orders o 
                     JOIN Adressen a ON o.adresId = a.adresId 
                     WHERE o.orderId = :orderId';
        $orderParams = ['orderId' => $orderId];
        $order = $sqlController->sql($orderSql)->params($orderParams)->run();

        if (!$order) {
            echo '<div class="error-message">Order not found.</div>';
            return;
        }

    // Voor producten en omschrijvingen is er een kolom met Engels en Nederlands in de database, deze wordt opgehaald via de Language class
    $language = new \Resources\Config\Language();
    $productLanguage = $language->productLanguage();

        $order = $order[0];

        $orderItemsSql = 'SELECT p.' . $productLanguage . ', orr.aantal, p.prijs 
                          FROM Orderregels orr 
                          JOIN Producten p ON orr.productId = p.productId 
                          WHERE orr.orderId = :orderId';
        $orderItemsParams = ['orderId' => $orderId];
        $orderItems = $sqlController->sql($orderItemsSql)->params($orderItemsParams)->run();

        ?>
        <div class="order-confirmation-container">
            <h2><?= __('order_bevestiging'); ?></h2>
            <div class="order-details">
                <h3><?= __('order_details'); ?></h3>
                <p><strong><?= __('order_id'); ?>:</strong> <?= htmlspecialchars($order['orderId']); ?></p>
                <p><strong><?= __('order_datum'); ?>:</strong> <?= htmlspecialchars($order['orderdatum']); ?></p>
                <p><strong><?= __('totale_prijs'); ?>:</strong> €<?= htmlspecialchars($order['totaalprijs']); ?></p>
                <p><strong><?= __('adres'); ?>:</strong> <?= htmlspecialchars($order['vestigingsnaam']); ?>, <?= htmlspecialchars($order['postcode']); ?>, <?= htmlspecialchars($order['huisnummer']); ?><?= htmlspecialchars($order['toevoeging']); ?></p>
            </div>
            <div class="order-items">
                <h3><?= __('order_artikelen'); ?></h3>
                <table>
                    <thead>
                        <tr>
                            <th><?= __('product'); ?></th>
                            <th><?= __('hoeveelheid'); ?></th>
                            <th><?= __('prijs'); ?></th>
                            <th><?= __('totaal'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderItems as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item[$productLanguage]); ?></td>
                                <td><?= htmlspecialchars($item['aantal']); ?></td>
                                <td>€<?= htmlspecialchars($item['prijs']); ?></td>
                                <td>€<?= htmlspecialchars($item['prijs'] * $item['aantal']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="actions">
                <a href="/klantproducten"><?= __('verder_winkelen'); ?></a>
                <a href="/welkom"><?= __('welkom'); ?></a>
                <a href="/order"><?= __('mijn_bestellingen'); ?></a>
            </div>
        </div>
        <?php
    }
}

new OrderConfirmationView();
