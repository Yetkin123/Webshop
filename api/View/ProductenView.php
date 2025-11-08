<?php

namespace View;

use Controller\SqlController;

class ProductenView extends Framework\AuthenticatedLayout
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

        $language = new \Resources\Config\Language();
        $productLanguage = $language->productLanguage();
        $descriptionLanguage = $language->descriptionLanguage();

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'] ?? 1;
            if (!isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId] = 0;
            }
            $_SESSION['cart'][$productId] += $quantity;
        }

        $search = '';
        $sqlController = SqlController::setup();
        $producten = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
            $search = $_POST['search'];
            $sql = 'SELECT * FROM Producten WHERE naam LIKE :search';
            $params = ['search' => "%$search%"];
            $producten = $sqlController->sql($sql)->params($params)->run();

            if (empty($producten)) {
                $checkSql = 'SELECT * FROM Zoektermen WHERE zoekterm = :zoekterm';
                $checkParams = ['zoekterm' => $search];
                $existingLog = $sqlController->sql($checkSql)->params($checkParams)->run();

                if ($existingLog) {
                    $updateSql = 'UPDATE Zoektermen SET aantal = aantal + 1, tijdstip = NOW() WHERE zoekterm = :zoekterm';
                    $sqlController->sql($updateSql)->params($checkParams)->run(false);
                } else {
                    $logSql = 'INSERT INTO Zoektermen (zoekterm, aantal, tijdstip) VALUES (:zoekterm, :aantal, NOW())';
                    $logParams = [
                        'zoekterm' => $search,
                        'aantal' => 1
                    ];
                    $sqlController->sql($logSql)->params($logParams)->run(false);
                }
            }
        } else {
            $sql = 'SELECT * FROM Producten';
            $producten = $sqlController->sql($sql)->run();
        }

        ?>
        <div class="products-container">
            <h2><?= __('onze_producten'); ?></h2>
            <form method="post" action="">
                <input type="text" name="search" placeholder="<?= __('zoek_producten'); ?>" value="<?= htmlspecialchars($search); ?>">
                <button type="submit"><?= __('zoeken'); ?></button>
            </form>
            <div class="product-list">
                <?php if ($producten): ?>
                    <?php foreach ($producten as $product): ?>
                        <div class="product-item">
                            <img src="<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product[$productLanguage]); ?>">
                            <h3><?= htmlspecialchars($product[$productLanguage]); ?></h3>
                            <p><?= htmlspecialchars($product[$descriptionLanguage]); ?></p>
                            <p>€<?= htmlspecialchars($product['prijs']); ?></p>
                            <form method="post" action="">
                                <input type="hidden" name="product_id" value="<?= $product['productId']; ?>">
                                <input type="number" name="quantity" value="1" min="1">
                                <button type="submit"><?= __('voeg_toe_aan_winkelwagen'); ?></button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p><?= __('geen_producten_gevonden'); ?></p>
                <?php endif; ?>
            </div>
            <a href="/welkom"><?= __('terug_naar_welkom'); ?></a>
            <a href="/winkelwagen"><?= __('ga_naar_winkelwagen'); ?></a>
        </div>
        <?php
    }
}

new ProductenView();
?>
