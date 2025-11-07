<?php

namespace View;

use Controller\SqlController;

class OrderView extends Framework\AuthenticatedLayout
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

        $userId = $_SESSION['user_id'];
        $sqlController = SqlController::setup();

        $sql = 'SELECT o.*, a.vestigingsnaam, a.postcode, a.huisnummer, a.toevoeging 
                FROM Orders o 
                JOIN Adressen a ON o.adresId = a.adresId 
                JOIN Klanten k ON a.klantId = k.klantId 
                JOIN Accounts ac ON k.accountId = ac.accountId 
                WHERE ac.accountId = :accountId';
        $params = ['accountId' => $userId];
        $orders = $sqlController->sql($sql)->params($params)->run();

        $language = new \Resources\Config\Language();
        $productLanguage = $language->productLanguage();

        ?>
        <div class="orders-container">
            <h2><?= __('uw_bestellingen'); ?></h2>
            <?php if ($orders): ?>
                <table>
                    <thead>
                        <tr>
                            <th><?= __('order_id'); ?></th>
                            <th><?= __('order_datum'); ?></th>
                            <th><?= __('adres'); ?></th>
                            <th><?= __('order_artikelen'); ?></th>
                            <th><?= __('totale_prijs'); ?></th>
                            <th><?= __('betaald'); ?></th>
                            <th><?= __('actie'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order) : ?>
                            <tr>
                                <td><?= htmlspecialchars($order['orderId']); ?></td>
                                <td><?= htmlspecialchars($order['orderdatum']); ?></td>
                                <td>
                                    <?= htmlspecialchars($order['vestigingsnaam']) . ', ' . htmlspecialchars($order['postcode']) . ' ' . htmlspecialchars($order['huisnummer']) . ' ' . htmlspecialchars($order['toevoeging']); ?>
                                </td>
                                <td>
                                    <?php
                                    $orderDetailsSql = 'SELECT p.' . $productLanguage . ', orr.aantal, p.prijs 
                                                        FROM Orderregels orr 
                                                        JOIN Producten p ON orr.productId = p.productId 
                                                        WHERE orr.orderId = :orderId';
                                    $orderDetailsParams = ['orderId' => $order['orderId']];
                                    $orderDetails = $sqlController->sql($orderDetailsSql)->params($orderDetailsParams)->run();
                                    $orderTotal = 0;
                                    foreach ($orderDetails as $detail) {
                                        echo htmlspecialchars($detail[$productLanguage]) . ' x ' . htmlspecialchars($detail['aantal']) . ' - €' . htmlspecialchars($detail['prijs']) . '<br>';
                                        $orderTotal += $detail['aantal'] * $detail['prijs'];
                                    }
                                    ?>
                                </td>
                                <td>€<?= htmlspecialchars($orderTotal); ?></td>
                                <td><?= $order['betaald'] ? __('ja') : __('nee'); ?></td>
                                <td>
                                    <?php if (!$order['betaald']): ?>
                                        <form method="get" action="/order_betalen">
                                            <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['orderId']); ?>">
                                            <button type="submit" class="pay-button"><?= __('betalen'); ?></button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p><?= __('geen_bestellingen'); ?></p>
            <?php endif; ?>
            <a href="/welkom"><?= __('terug_naar_welkom'); ?></a>
        </div>
        <?php
    }
}

new OrderView();
?>
