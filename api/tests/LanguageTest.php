<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use Resources\Config\Language;

require_once __DIR__ . '/../vendor/autoload.php';

class LanguageTest extends TestCase
{
    public function testTranslation()
    {
        // Mock sessievariabele voor testen
        $_SESSION['lang'] = 'nl';

        // Instantieer de Language klasse
        $language = new Language();

        // Simuleer dat de Language klasse woorden heeft geladen (normaal gebeurt dit in de constructor)
        $language->listwithwords = [
            'hello' => 'Hallo',
            // Voeg hier andere vertalingen toe die je wilt testen
        ];

        // Simuleer een vertaalde sleutel en verwachte vertaling
        $key = 'hello';
        $expectedTranslation = 'Hallo'; // Verwacht dat 'hello' in 'nl' vertaald wordt naar 'Hallo'

        // Roep de vertaalde functie aan
        $actualTranslation = $language->translate($key);

        // Assert dat de vertaling correct is
        $this->assertEquals($expectedTranslation, $actualTranslation);
    }

    public function testCreateUrl()
    {
        // Start de sessie en stel de taal in (bijvoorbeeld 'nl')
        session_start();
        $_SESSION['lang'] = 'nl';

        // Instantieer de Language klasse
        $language = new Language();

        // Simuleer een pad
        $path = '/example';

        // Genereer de verwachte URL
        $expectedUrl = '/example?lang=nl';

        // Roep de createUrl functie aan
        $actualUrl = $language->createUrl($path);

        // Assert dat de gegenereerde URL correct is
        $this->assertEquals($expectedUrl, $actualUrl);
    }
}
