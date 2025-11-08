<?php

namespace Controller;

class AbonnementController
{
    public static function getVestigingsnamen(): array
    {
        try {
            // Haal accountId op uit de sessie
            $accountId = $_SESSION['user_id'] ?? null;
    
            if (!$accountId) {
                return []; // Gebruiker niet ingelogd, accountId niet beschikbaar
            }
    
            // Stap 1: Haal het KvK-nummer op van de gebruiker
            $kvkQuery = 'SELECT kvkNummer FROM Klanten WHERE accountId = :accountId';
            $kvkResult = SqlController::setup()
                ->sql($kvkQuery)
                ->params(['accountId' => $accountId])
                ->run();
    
            if (!$kvkResult || !isset($kvkResult[0]['kvkNummer'])) {
                return []; // Geen KvK-nummer gevonden
            }
    
            $kvkNummer = $kvkResult[0]['kvkNummer'];
    
            // Stap 2: Haal alle vestigingsnamen op met hetzelfde KvK-nummer
            $query = 'SELECT Adressen.adresId, Adressen.vestigingsnaam
                        FROM Adressen
                        JOIN Klanten ON Adressen.klantId = Klanten.klantId
                        WHERE Klanten.kvkNummer = :kvkNummer';
            $result = SqlController::setup()
                ->sql($query)
                ->params(['kvkNummer' => $kvkNummer])
                ->run();
    
            return $result ?: []; // Return lege array als er geen resultaten zijn
        } catch (\PDOException $e) {
            echo 'Database Error: ' . $e->getMessage();
            return []; // Fout bij het uitvoeren van de query
        }
    }

    public static function getProducten(): array
    {
        try {
            return SqlController::setup()
                ->sql('SELECT * FROM Producten')
                ->run();
        } catch (\PDOException $e) {
            echo 'Database Error: ' . $e->getMessage();
            return [];
        }
    }

    public static function saveAbonnement(): void
    {
        self::saveGeneralData();
        $abonnement = self::getLastAbonnement();
        self::addAbonnementsProducten($abonnement[0]['abonnementId']);
    }

    private static function saveGeneralData()
    {
        $language = new \Resources\Config\Language();
        $descriptionLanguage = $language->descriptionLanguage();
        
        $naam = $_POST['naam'];
        $omschrijving = $_POST[$descriptionLanguage];
        $ingangsdatum = $_POST['ingangsdatum'];
        $abonnementsperiode = $_POST['abonnementsperiode'];
        $capaciteit = $_POST['capaciteit'];
        $adres = $_POST['adres'];
    
        $query = 'INSERT INTO Abonnementen (naam, ' . $descriptionLanguage . ', afsluitdatum, ingangsdatum, abonnementsperiode, capaciteit, adresId)
                    VALUES (:naam, :' . $descriptionLanguage . ', CURRENT_DATE(), :ingangsdatum, :abonnementsperiode, :capaciteit, :adresId)';
      
        SqlController::setup()
            ->sql($query)
            ->params([
                'naam' => $naam,
                $descriptionLanguage => $omschrijving,
                'ingangsdatum' => $ingangsdatum,
                'abonnementsperiode' => $abonnementsperiode,
                'capaciteit' => $capaciteit,
                'adresId' => $adres
            ])
            ->run(false);
    }

    private static function getLastAbonnement(): array
    {
        $product = SqlController::setup()
            ->sql('SELECT * FROM Abonnementen ORDER BY abonnementId DESC LIMIT 1')
            ->run(true);

        return $product;
    }

    private static function addAbonnementsProducten($abonnementId): void
    {
        $producten = self::getProducten();
    
        foreach ($producten as $product) {
            $productId = $product['productId']; // Haal productId op van het product
            
            // Controleer of checkbox is aangevinkt voor dit productId
            if (isset($_POST[$productId])) {
                self::addAbonnementsProduct($abonnementId, $productId);
            }
        }
    }
    
    private static function addAbonnementsProduct($abonnementId, $productId): void
    {
        SqlController::setup()
            ->sql('INSERT INTO AbonnementsProducten (abonnementId, productId, aantal) VALUES (?, ?, ?)')
            ->params([
                $abonnementId,
                $productId,
                1 // Standaard aantal, kan worden aangepast indien nodig
            ])
            ->run(false);
    }
}
