<?php

namespace View;

use Resources\Config\Language;
use Controller\AccountController;


class WelcomeView extends Framework\AuthenticatedLayout
{

    function content()
    {
        $language = new Language();
        $controller = new AccountController();
        $name = $controller->getName();
        ?>
        <div class="welcome-container">
            <h2><?= __('welkom') . " " . $name ?></h2>
            <p><?= __('welkomtekst1'); ?></p>
            <p><?= __('welkomtekst2'); ?></p>
            <?php
            if (isset($_SESSION['accounttype'])) {
                $accounttype = $_SESSION['accounttype'];
                if ($accounttype == 'Beheerder' || $accounttype == 'Medewerker') {
                    echo '<a href=' . $language->createUrl('producten') . '>' . __('bekijk_producten') . '</a>';
                } elseif ($accounttype == 'Klant') {
                    echo '<a href=' . $language->createUrl('klantproducten') . '>' . __('bekijk_webshop') . '</a>';
                }
            } else {
                header('Location: ' . $language->createUrl('/inloggen'));
                exit();
            }
            ?>
            <br><br>
            <a href="/?action=logout"><?= __('uitloggen'); ?></a>
        </div>
        <?php
    }
}

new WelcomeView();