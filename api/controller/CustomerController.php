<?php

namespace Controller;

use Resources\Config\Language;

class CustomerController
{
    public static function setup(): CustomerController
    {
        return new CustomerController;
    }

    public function getUserDataFromSession()
    {

        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];

            // Voorbeeld SQL-query om abonnementsproducten van de gebruiker op te halen
            $subscriptions = $this->getUserSubscriptions($user_id);

            // Voorbeeld SQL-query om services van de gebruiker op te halen
            $services = $this->getUserServices($user_id);

            // Voorbeeld SQL-query om facturen van de gebruiker op te halen
            $invoices = $this->getUserInvoices($user_id);

            // Voorbeeld SQL-query om betaalgegevens van de gebruiker op te halen
            $paymentMethods = $this->getUserPaymentMethods($user_id);

            // Voorbeeld SQL-query om automatische incasso's van de gebruiker op te halen
            $directDebits = $this->getUserDirectDebits($user_id);

            // Geef alle verzamelde gegevens terug als array
            return [
                'subscriptions' => $subscriptions,
                'services' => $services,
                'invoices' => $invoices,
                'paymentMethods' => $paymentMethods,
                'directDebits' => $directDebits,
            ];
        } else {
            return null; // Of een foutmelding als de gebruiker niet is ingelogd
        }
    }

    private function getUserSubscriptions($user_id)
    {
        $sql = "
        SELECT ap.*, 
               p.naam AS product_naam, 
               p.naam_en AS product_naam_en, 
               ap.aantal AS product_aantal,
               a.naam as abonnement_naam, 
               a.afgesloten AS afgesloten, 
               a.abonnementId AS abonnement_id
        FROM AbonnementsProducten ap
        INNER JOIN Abonnementen a ON ap.abonnementId = a.abonnementId
        INNER JOIN Producten p ON ap.productId = p.productId
        INNER JOIN Adressen ad ON a.adresId = ad.adresId
        INNER JOIN Klanten k ON ad.klantId = k.klantId
        WHERE k.accountId = :user_id
    ";

        $params = [
            'user_id' => $user_id,
        ];

        $sqlController = SqlController::setup();
        return $sqlController->sql($sql)
            ->params($params)
            ->run();
    }

    private function getUserServices($user_id)
    {
        $sql = "
            SELECT s.*
            FROM Services s
            INNER JOIN Adressen ad ON s.adresId = ad.adresId
            INNER JOIN Klanten k ON ad.klantId = k.klantId
            WHERE k.accountId = :user_id
        ";

        $sqlController = SqlController::setup();
        return $sqlController->sql($sql)
            ->params(['user_id' => $user_id])
            ->run();
    }

    private function getUserInvoices($user_id)
    {
        $sql = "
            SELECT f.*
            FROM Facturen f
            INNER JOIN Orders o ON f.orderId = o.orderId
            INNER JOIN Adressen ad ON o.adresId = ad.adresId
            INNER JOIN Klanten k ON ad.klantId = k.klantId
            WHERE k.accountId = :user_id
        ";

        $sqlController = SqlController::setup();
        return $sqlController->sql($sql)
            ->params(['user_id' => $user_id])
            ->run();
    }

    private function getUserPaymentMethods($user_id)
    {
        $sql = "
            SELECT bg.*
            FROM Betaalgegevens bg
            INNER JOIN AutomatischeIncassos ai ON bg.betaalId = ai.betaalId
            INNER JOIN Abonnementen a ON ai.abonnementId = a.abonnementId
            INNER JOIN Adressen ad ON a.adresId = ad.adresId
            INNER JOIN Klanten k ON ad.klantId = k.klantId
            INNER JOIN Accounts ac ON k.accountId = ac.accountId
            WHERE ac.accountId = :user_id
        ";

        $sqlController = SqlController::setup();
        return $sqlController->sql($sql)
            ->params(['user_id' => $user_id])
            ->run();
    }


    private function getUserDirectDebits($user_id)
    {
        $sql = "
            SELECT ai.*
            FROM AutomatischeIncassos ai
            INNER JOIN Abonnementen a ON ai.abonnementId = a.abonnementId
            INNER JOIN Adressen ad ON a.adresId = ad.adresId
            INNER JOIN Klanten k ON ad.klantId = k.klantId
            WHERE k.accountId = :user_id
        ";

        $sqlController = SqlController::setup();
        return $sqlController->sql($sql)
            ->params(['user_id' => $user_id])
            ->run();
    }
}
