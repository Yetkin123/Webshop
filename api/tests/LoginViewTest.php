<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use View\LoginView;

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../resources/config/PublicFunctions.php';

class LoginViewTest extends TestCase
{
    public function testShow()
    {
        // Mockt sessievariabele voor testen
        $_SESSION['lang'] = 'en';
        $_SERVER['REQUEST_URI'] = '/inloggen?lang=en';
        
        // Maakt een instantie van LoginView
        $view = new LoginView();

        // Start met het bufferen van de output
        ob_start();

        // Roept de show() methode aan om HTML te genereren
        $view->show();

        // Haalt de inhoud van de outputbuffer op en sluit deze af
        $output = ob_get_clean();

        // Asserties
        $this->assertSame('en', $_SESSION['lang']);
        $this->assertNotSame('nl', $_SESSION['lang']);
        $this->assertStringContainsString('<html lang="en">', $output);
        $this->assertStringContainsString('<title>Log in</title>', $output);
        $this->assertNotEquals('<h2>Log in</h2>', $output);
        $this->assertNotEquals('<h2>Inloggen</h2>', $output);
        $this->assertStringNotContainsString('<p class="error">', $output); // $error is niet ingesteld, dus geen foutmelding
        $this->assertStringContainsString('<label for="username">E-mail address:</label>', $output);
        $this->assertStringContainsString('<label for="password">Password:</label>', $output);
        $this->assertStringContainsString('<button type="submit">Log in</button>', $output);

        ob_clean();
    }
}