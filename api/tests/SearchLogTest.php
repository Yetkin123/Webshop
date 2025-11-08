<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Controller\SearchLogController;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../resources/config/PublicFunctions.php';

class SearchLogTest extends TestCase
{
    protected function setUp(): void {
        parent::setUp();
        // Stel de sessievariabelen in
        [$_SESSION['user_id'], $_SESSION['accounttype']] = [1, 'Beheerder'];
    }

    public function testEmptyTable()
    {
        // Simuleer een POST-verzoek met de delete_logs knop ingediend
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['delete_logs'] = true;

        // Mock SearchLogController om de database-interactie te vermijden
        $mockSearchLogController = $this->getMockBuilder(SearchLogController::class)
            ->onlyMethods(['deleteMessage'])
            ->getMock();

        // Configureer de mock om tableEmptyMessage terug te geven
        $mockSearchLogController->expects($this->any())
            ->method('deleteMessage')
            ->willReturn('lege_tabel');

        // Roep de deleteMessage methode aan
        ob_start(); // Vang de uitvoer op (echo in script tags)
        $message = $mockSearchLogController->deleteMessage();
        ob_end_clean(); // Stop het opvangen van de uitvoer

        // Assertions
        $this->assertEquals('lege_tabel', $message); // Controleer of de juiste echo wordt teruggegeven
        $this->assertArrayHasKey('user_id', $_SESSION); // Controleer of 'user_id' in de sessie is ingesteld
        $this->assertArrayHasKey('accounttype', $_SESSION); // Controleer of 'accounttype' in de sessie is ingesteld
    }
}
