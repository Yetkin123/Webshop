<?php

namespace Controller;

class ManagementController
{
    public static function getOrders(): array
    {
        return SqlController::setup()
            ->sql('SELECT * FROM `Orders`')
            ->run();
    }

    public static function getAdressen(): array
    {
        return SqlController::setup()
            ->sql('SELECT * FROM `Adressen`')
            ->run();
    }

    public static function getAbonnementen(): array
    {
        return SqlController::setup()
            ->sql('SELECT * FROM `Abonnementen`')
            ->run();
    }

    public static function getProducten(): array
    {
        return SqlController::setup()
            ->sql('SELECT * FROM `Producten`')
            ->run();
    }

    public static function getBetaalgegevens(): array
    {
        return SqlController::setup()
            ->sql('SELECT * FROM `Betaalgegevens`')
            ->run();
    }

    public static function getKlanten(): array
    {
        return SqlController::setup()
            ->sql('SELECT * FROM `Klanten`')
            ->run();
    }

    public static function openCsv()
    {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv'])) {
            $file = $_FILES['csv'];

            if ($file['error'] === UPLOAD_ERR_OK) {
                // Verkrijg de tijdelijke bestandsnaam
                $tmpFilePath = $file['tmp_name'];

                // Open de tijdelijke bestandsnaam als een stream
                if (($handle = fopen($tmpFilePath, 'r')) !== FALSE) {
                    // Sla het CSV-bestand op door de regels te lezen en te verwerken
                    self::saveCsvFile($_POST['class'], $handle);

                    // Sluit de stream
                    fclose($handle);
                } else {
                    echo __('kon_bestand_niet_openen_als_stream') . "\n";
                }
            } else {
                echo __('fout_tijdens_uploaden') . "\n";
            }
        } else {
            echo __('geen_upload') . "\n";
        }
    }

    public static function saveCsvFile($class, $fileData)
    {
        // Lees elke regel van de CSV-bestand en verwerk deze
        var_dump($fileData);
        while (($row = fgetcsv($fileData, 1000, ",")) !== FALSE) {
            self::execQuery($class, $row);
            var_dump($row);
        }
    }

    private static function execQuery($class, $data)
    {
        $controller = SqlController::setup()->params($data);

        switch ($class) {
            case 'Adressen':
                $controller->sql('INSERT INTO Adressen (vestigingsnaam, postcode, huisnummer, toevoeging, klantId, contactId, emailId) VALUES (?, ?, ?, ?, ?, ?, ?)');
                break;
            case 'Orders':
                $controller->sql('INSERT INTO Orders (status, totaalprijs, orderdatum, adresId) VALUES (?, ?, ?, ?)');
                break;
            case 'Abonnementen':
                $controller->sql('INSERT INTO Abonnementen (naam, omschrijving, omschrijving_en, prijs, afsluitdatum, ingangsdatum, abonnementsperiode, capaciteit, adresId) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
                break;
            case 'Producten':
                $controller->sql('INSERT INTO Producten (naam, naam_en, omschrijving, omschrijving_en, prijs, image) VALUES (?, ?, ?, ?, ?, ?)');
                break;
            case 'Betaalgegevens':
                $controller->sql('INSERT INTO Betaalgegevens (rekeningnummer, betaalmethode, betalingstype, betaaldatum) VALUES (?, ?, ?, ?)');
                break;
            case 'Klanten':
                $controller->sql('INSERT INTO Klanten (kvkNummer, accountId) VALUES (?, ?)');
                break;
        }

        $controller->run(false);
        header('Location: /welkom');
    }

    public static function uploadImages()
    {
        if (isset($_FILES['images'])) {

            $images = $_FILES['images'];

            // Map waar de afbeeldingen opgeslagen moeten worden
            $uploadDir = __DIR__ . '/../Resources/uploads/';

            // Controleer of de uploadmap bestaat, zo niet, maak deze aan
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Bestandsinformatie
            $fileName = basename($images['name']);
            $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Toegestane bestandstypen
            $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');

            // Controleer of het bestand een toegestaan type is
            if (in_array($fileType, $allowedTypes)) {
                // Controleer of er geen fout opgetreden is tijdens het uploaden
                if ($images['error'] === UPLOAD_ERR_OK) {
                    // Genereer een unieke bestandsnaam
                    $uniqueFileName = pathinfo($fileName, PATHINFO_FILENAME) . '_' . uniqid() . '.' . $fileType;
                    $targetFilePath = $uploadDir . $uniqueFileName;

                    // Verplaats het bestand naar de uploadmap
                    if (move_uploaded_file($images['tmp_name'], $targetFilePath)) {
                        echo __('het_bestand') . htmlspecialchars($uniqueFileName) . __('succesvol_geupload');
                    } else {
                        echo __('fout_tijdens_uploaden');
                    }
                } else {
                    echo __('fout_tijdens_uploaden_specifiek_bestand') . $_FILES['image']['error'];
                }
            } else {
                echo __('alleen_jpg_png_gif');
            }
        } else {
            echo __('geen_upload');
        }
    }
}
