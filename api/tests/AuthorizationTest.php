<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Controller\AuthorizationController;

require_once __DIR__ . '/../vendor/autoload.php';

class AuthorizationTest extends TestCase
{
    public function testAuthorizeWithCorrectAccountType(): void
    {
        // Mock de sessie met een gebruiker die is ingelogd met het juiste accounttype
        $_SESSION['user_id'] = 1;
        $_SESSION['accounttype'] = 'Beheerder';

        // Mock de vereiste accounttypes voor deze pagina
        $requiredAccountTypes = ['Beheerder', 'Medewerker'];
        
        // Roep de authorize methode aan
        AuthorizationController::authorize($requiredAccountTypes);

        // Asserties
        $this->assertTrue(isset($_SESSION['user_id'])); // Controleer of 'user_id' is ingesteld
        $this->assertTrue(isset($_SESSION['accounttype'])); // Controleer of 'accounttype' is ingesteld

        // Verwijder de sessievariabelen na de test
        unset($_SESSION['user_id']);
        unset($_SESSION['accounttype']);
    }
}