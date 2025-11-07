<?php

namespace View\Framework;

use Resources\Config\Language;

abstract class AuthenticatedLayout extends View
{
    abstract function content();

    function show()
    {
        $language = new Language();
        if (!isset($_SESSION['user_id'])) {
            header('Location:' . $language->createUrl('/inloggen'));
            exit;
        }
        ?>
        <!DOCTYPE html>
        <html lang="<?= $_SESSION['lang'] ?? 'nl' ?>">

        <head>
            <meta charset="UTF-8">
            <title><?= __('title'); ?></title>
            <link rel="stylesheet" href="/Resources/css/output.css">
            <link rel="stylesheet" href="/Resources/css/stylewebshop.css">
        </head>

        <body>
            <?php
            if (isset($_SESSION['accounttype'])) {
                $accounttype = $_SESSION['accounttype'];

                if ($accounttype == 'Beheerder' || $accounttype == 'Medewerker') {
                    include_once './View/templates/header.php';
                } elseif ($accounttype == 'Klant') {
                    include_once './View/templates/headerCustomers.php';
                }
            } else {
                header('Location: ' . $language->createUrl('/inloggen'));
                exit();
            }

            $this->content();
            ?>
        </body>

        </html>
        <?php
    }
}
