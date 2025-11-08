<?php

namespace View;

use Controller\SqlController;

ob_start();

class CartView extends Framework\AuthenticatedLayout
{
    function content()
    {
        $this->ensureSessionStarted();
        $this->ensureUserIsLoggedIn();
        $this->initializeCart();

        $sqlController = SqlController::setup();

        $errorMessage = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostRequest($sqlController, $errorMessage);
        }

        $cartItems = $_SESSION['cart'];
        $productIds = array_keys($cartItems);
        $products = $this->getProducts($sqlController, $productIds);
        $language = new \Resources\Config\Language();
        $productLanguage = $language->productLanguage();

        $this->renderCart($products, $productLanguage, $cartItems, $errorMessage);
    }

    private function ensureSessionStarted()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function ensureUserIsLoggedIn()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /inloggen');
            exit;
        }
    }

    private function initializeCart()
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    private function handlePostRequest($sqlController, &$errorMessage)
    {
        if (isset($_POST['update_cart'])) {
            $this->updateCart();
        } elseif (isset($_POST['remove_item'])) {
            $this->removeItemFromCart();
        } elseif (isset($_POST['checkout'])) {
            try {
                $this->checkout($sqlController);
            } catch (\Exception $e) {
                $errorMessage = $e->getMessage();
            }
        }
    }

    private function updateCart()
    {
        foreach ($_POST['quantities'] as $productId => $quantity) {
            if ($quantity > 0) {
                $_SESSION['cart'][$productId] = $quantity;
            } else {
                unset($_SESSION['cart'][$productId]);
            }
        }
    }

    private function removeItemFromCart()
    {
        $productId = $_POST['product_id'];
        unset($_SESSION['cart'][$productId]);
    }

    private function getProducts($sqlController, $productIds)
    {
        if (empty($productIds)) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));
        $sql = "SELECT * FROM Producten WHERE productId IN ($placeholders)";
        return $sqlController->sql($sql)->params($productIds)->run();
    }

    private function renderCart($products, $productLanguage, $cartItems, $errorMessage)
    {
        ?>
        <div class="cart-container">
            <h2><?= __('winkelwagen'); ?></h2>
            <?php if ($errorMessage): ?>
                <div class="error-message"><?= htmlspecialchars($errorMessage); ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <table>
                    <thead>
                        <tr>
                            <th><?= __('product'); ?></th>
                            <th><?= __('hoeveelheid'); ?></th>
                            <th><?= __('prijs'); ?></th>
                            <th><?= __('totaal'); ?></th>
                            <th><?= __('actie'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <?php
                                $productId = $product['productId'];
                                $quantity = $cartItems[$productId];
                                $totalPrice = $quantity * $product['prijs'];
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($product[$productLanguage]); ?></td>
                                    <td>
                                        <input type="number" name="quantities[<?= $productId; ?>]" value="<?= $quantity; ?>" min="1" class="quantity-input">
                                    </td>
                                    <td>€<?= htmlspecialchars($product['prijs']); ?></td>
                                    <td>€<?= htmlspecialchars($totalPrice); ?></td>
                                    <td>
                                        <button type="submit" name="remove_item" value="1" class="remove-button"><?= __('verwijderen'); ?></button>
                                        <input type="hidden" name="product_id" value="<?= $productId; ?>">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="5">
                                    <button type="submit" name="update_cart"><?= __('update_winkelwagen'); ?></button>
                                </td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td colspan="5"><?= __('winkelwagen_leeg'); ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </form>
            <?php if (!empty($products)): ?>
                <form method="post" action="">
                    <button type="submit" name="checkout"><?= __('afrekenen'); ?></button>
                </form>
            <?php endif; ?>
            <a href="/klantproducten"><?= __('verder_winkelen'); ?></a>
        </div>
        <?php
        ob_end_flush();
    }

    private function checkout($sqlController)
    {
        if (empty($_SESSION['cart'])) {
            header('HTTP/1.1 400 Bad Request');
            throw new \Exception(__("geen_producten"));
        }

        $userId = $_SESSION['user_id'];
        $cartItems = $_SESSION['cart'];
        $totalPrice = 0;

        $address = $this->getAddress($sqlController, $userId);
        if (!$address) {
            throw new \Exception(__("geen_adres"));
        }

        $addressId = $address[0]['adresId'];
        $orderId = $this->createOrder($sqlController, $addressId);

        foreach ($cartItems as $productId => $quantity) {
            $product = $this->getProduct($sqlController, $productId);
            if ($product) {
                $price = $product[0]['prijs'];
                $totalPrice += $price * $quantity;
                $this->createOrderItem($sqlController, $orderId, $productId, $price, $quantity);
            }
        }

        $this->updateOrderTotal($sqlController, $orderId, $totalPrice);

        unset($_SESSION['cart']);
        $_SESSION['last_order_id'] = $orderId;

        header('Location: /order_bevestiging');
        exit;
    }

    private function getAddress($sqlController, $userId)
    {
        $sql = 'SELECT adresId FROM Adressen WHERE klantId = (SELECT klantId FROM Klanten WHERE accountId = :accountId)';
        $params = [':accountId' => $userId];
        return $sqlController->sql($sql)->params($params)->run();
    }

    private function createOrder($sqlController, $addressId)
    {
        $orderSql = 'INSERT INTO Orders (status, totaalprijs, orderdatum, adresId) VALUES ("Winkelmand", 0, NOW(), :adresId)';
        $orderParams = [':adresId' => $addressId];
        $sqlController->sql($orderSql)->params($orderParams)->run(false);
        return $this->getLastInsertId($sqlController);
    }

    private function getProduct($sqlController, $productId)
    {
        $productSql = 'SELECT prijs FROM Producten WHERE productId = :productId';
        $productParams = [':productId' => $productId];
        return $sqlController->sql($productSql)->params($productParams)->run();
    }

    private function createOrderItem($sqlController, $orderId, $productId, $price, $quantity)
    {
        $orderItemSql = 'INSERT INTO Orderregels (orderId, productId, factuurprijs, aantal) VALUES (:orderId, :productId, :factuurprijs, :aantal)';
        $orderItemParams = [
            ':orderId' => $orderId,
            ':productId' => $productId,
            ':factuurprijs' => $price,
            ':aantal' => $quantity,
        ];
        $sqlController->sql($orderItemSql)->params($orderItemParams)->run(false);
    }

    private function updateOrderTotal($sqlController, $orderId, $totalPrice)
    {
        $updateOrderSql = 'UPDATE Orders SET totaalprijs = :totaalprijs WHERE orderId = :orderId';
        $updateOrderParams = [
            ':totaalprijs' => $totalPrice,
            ':orderId' => $orderId,
        ];
        $sqlController->sql($updateOrderSql)->params($updateOrderParams)->run(false);
    }

    private function getLastInsertId($sqlController)
    {
        $reflection = new \ReflectionClass($sqlController);
        $property = $reflection->getProperty('pdo');
        $property->setAccessible(true);
        $pdo = $property->getValue($sqlController);
        return $pdo->lastInsertId();
    }
}

new CartView();
