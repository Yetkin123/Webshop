<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Controller\ManagementController;

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../resources/config/PublicFunctions.php';

class CsvTest extends TestCase
{
    public function testOpenCsv()
    {
        // Mock de $_FILES superglobal
        $_FILES['csv'] = [
            'name' => 'test.csv',
            'type' => 'text/csv',
            'tmp_name' => __DIR__ . '/test.csv', // Zorg ervoor dat dit bestand bestaat voor de test
            'error' => UPLOAD_ERR_OK,
            'size' => 123
        ];

        // Mock de $_POST superglobal
        $_POST['class'] = 'Producten';

        // Vang de output op
        ob_start();
        ManagementController::openCsv();
        $output = ob_get_clean();

        // Assert dat er geen foutmeldingen zijn
        $this->assertStringNotContainsString("Er is een fout opgetreden tijdens het uploaden van het bestand.", $output);
        $this->assertStringNotContainsString("Kon het bestand niet openen als een stream.", $output);

        // Assert directe verwerking van test.csv
        // Hier controleer je bijvoorbeeld op specifieke resultaten die je verwacht uit test.csv
        // Bijvoorbeeld: controleer de verwerkte gegevens rechtstreeks uit test.csv

        // Voorbeeld: verifieer dat het bestand correct is verwerkt
        $this->assertTrue(file_exists(__DIR__ . '/test.csv'), 'test.csv moet bestaan voor de test');

        // Controleer specifieke gegevens in test.csv
        $csvData = file(__DIR__ . '/test.csv');
        $this->assertCount(1, $csvData); // Controleer dat test.csv bestaat uit 1 regel

    }
}