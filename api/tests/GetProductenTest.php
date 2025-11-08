<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../vendor/autoload.php';

use Controller\ManagementController;

class GetProductenTest extends TestCase {

    public function testGetProducten() {
        // Haal de gegevens op uit de database via ManagementController::getProducten()
        $producten = ManagementController::getProducten();
        $this->assertNotEmpty($producten); // Zorg ervoor dat er gegevens zijn opgehaald uit de database

        // Verwachte dat een van de producten uit ManagementController::getProducten() hiermee matcht
        $expectedProduct = ['AromaBliss','AromaBliss','Omschrijving van AromaBliss','Description of AromaBliss','795.00','resources/images/aroma_bliss.jpg'];        

        // Assert dat minstens één product overeenkomt met de verwachte gegevens
        $matchFound = false;
        foreach ($producten as $product) {
            if (
                $product['naam'] == $expectedProduct[0] &&
                $product['naam_en'] == $expectedProduct[1] &&
                $product['omschrijving'] == $expectedProduct[2] &&
                $product['omschrijving_en'] == $expectedProduct[3] &&
                $product['prijs'] == $expectedProduct[4] &&
                $product['image'] == $expectedProduct[5]
            ) {
                $matchFound = true;
                break;
            }
        }

        $this->assertTrue($matchFound, 'Geen overeenkomst gevonden tussen test.csv en databasegegevens');
    }
}