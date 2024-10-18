# Webshop
Dit is een webshop gebouwd in PHP. Voor de opmaak is Tailwind CSS gebruikt en de database is geschreven in SQL. Het "Home" gedeelte van de website is voornamelijk geschreven in HTML5, CSS3 en JavaScript.

Om de webshop werkend te krijgen plaats je de bestanden in je "C:\xampp\htdocs". Nadat je dit gedaan hebt, start XAMPP op. Klik hier op "Start" Apache en MySQL. Ga vervolgens in je browser naar "localhost/phpMyAdmin" en importeer de database in de volgende volgorde "1 - DDL.sql", "2 - Logic.sql" en "3 - DML.sql" (zie mapje "database"). Als je nu naar "localhost" gaat in je browser, dan krijg je een inlogpagina te zien. Hier kan je met "yetkin@yerothia.nl" met wachtwoord "test" inloggen.

## PHP Testen uitvoeren met PHPUnit
De PHP testen kunnen als volgt worden uitgevoerd nadat PHPUnit is geïnstalleerd.

php phpunit.phar tests/[bestandsnaam van de test].php

### Testbestanden testen:

php phpunit.phar tests/AuthorizationTest.php

php phpunit.phar tests/CsvTest.php

php phpunit.phar tests/GetProductenTest.php

php phpunit.phar tests/LanguageTest.php

php phpunit.phar tests/LoginViewTest.php

php phpunit.phar tests/SearchLogTest.php

php phpunit.phar tests/SqlControllerTest.php

php phpunit.phar tests/UploadImageTest.php

### Individuele methoden testen:

php phpunit.phar --filter testSetupReturnsInstanceOfSqlController tests/SqlControllerTest.php

php phpunit.phar --filter testDatabaseConstants tests/SqlControllerTest.php

php phpunit.phar --filter testUploadImagesSuccess tests/UploadImageTest.php

php phpunit.phar --filter testUploadImagesNoFile tests/UploadImageTest.php

php phpunit.phar --filter testTranslation tests/LanguageTest.php

php phpunit.phar --filter testCreateUrl tests/LanguageTest.php

### Alle testen tegelijk uitvoeren:

./vendor/bin/phpunit
