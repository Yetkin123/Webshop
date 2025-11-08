<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../resources/config/PublicFunctions.php';

use Controller\ManagementController;

class UploadImageTest extends TestCase
{
    public function testUploadImagesSuccess(): void
    {
        // Mock de sessievariabele voor de taal
        $_SESSION['lang'] = 'nl';
        
        // Mock de bestandsinformatie zoals het zou zijn na een upload via een formulier
        $fileName = 'test.jpg';
        $filePath = __DIR__ . '/test.jpg'; // Dit bestand moet op de opgegeven locatie bestaan

        // De map waar de afbeeldingen moeten worden geüpload
        $uploadDir = __DIR__ . '/../resources/uploads/';

        // Genereer een unieke bestandsnaam
        $uniqueFileName = uniqid() . '_' . $fileName;
        $targetFilePath = $uploadDir . $uniqueFileName;

        // Kopieer het bestand naar de uploadmap
        $success = copy($filePath, $targetFilePath);

        // Capture output (weergave opvangen)
        ob_start();
        if ($success) {
            echo "Het bestand " . htmlspecialchars($uniqueFileName) . " is succesvol geüpload.";
        } else {
            echo "Er is een fout opgetreden bij het uploaden van het bestand.";
        }
        $output = ob_get_clean();

        // Assertions
        $this->assertTrue($success); // Controleer of het bestand is gekopieerd naar de uploadmap
        $this->assertStringContainsString('Het bestand', $output); // Controleer of succesmelding wordt weergegeven
        $this->assertStringNotContainsString('Er is een fout', $output); // Controleer of er geen foutmelding wordt weergegeven

        // Controleer of het geüploade bestand exact overeenkomt met het originele bestand
        $this->assertFileEquals($filePath, $targetFilePath);
    }

    public function testUploadImagesNoFile(): void
    {
        // Mock de sessievariabele voor de taal
        $_SESSION['lang'] = 'nl';

        // Simuleer dat er geen bestand wordt geüpload (leeg $_FILES array)
        $_FILES = [];

        // Capture output
        ob_start();
        ManagementController::uploadImages();
        $output = ob_get_clean();

        // Assertions
        $this->assertStringContainsString('Geen bestand geüpload', $output); // Controleer of juiste melding wordt weergegeven
    }
}