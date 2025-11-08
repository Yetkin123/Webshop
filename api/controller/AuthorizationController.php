<?php

namespace Controller;

use Resources\Config\Language;

class AuthorizationController extends Controller
{
    public static function authorize($requiredAccountTypes)
    {

        // Controleer of de gebruiker is ingelogd
        if (!isset($_SESSION['user_id'])) {
            // Gebruiker is niet ingelogd, stuur ze naar de inlogpagina
            self::redirect('/inloggen');
        }

        // Controleer of de gebruiker een van de vereiste accounttypes heeft
        $userAccountType = $_SESSION['accounttype'] ?? null;

        if (!in_array($userAccountType, $requiredAccountTypes)) {
            // Gebruiker heeft niet het juiste accounttype, stuur ze naar de welkomstpagina of een andere pagina
            self::redirect('/welkom');
        }

        // Gebruiker heeft het juiste accounttype, laat ze doorgaan naar de pagina
    }

    private static function redirect($url)
    {
        $language = new Language();
        header('Location: ' . $language->createUrl($url));
        exit();
    }
}
