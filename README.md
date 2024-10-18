# Webshop
Dit is een webshop gebouwd in PHP. Start XAMPP op en start Apache en MySQL. Importeer de database in phpMyAdmin in de volgende volgorde, eerst '1 - DDL.sql', '2 - Logic.sql' vervolgens '3 - DML.sql'. Als je nu naar localhost gaat krijg je een inlogpagina te zien. Hier kan je met 'yetkin@yerothia.nl' met wachtwoord 'test' inloggen.

### PHP Testen uitvoeren met PHPUnit
De PHP testen kunnen als volgt worden uitgevoerd nadat PHPUnit is geïnstalleerd.

php phpunit.phar tests/[bestandsnaam van de test].php

Testbestanden testen:

php phpunit.phar tests/AuthorizationTest.php

php phpunit.phar tests/CsvTest.php

php phpunit.phar tests/GetProductenTest.php

php phpunit.phar tests/LanguageTest.php

php phpunit.phar tests/LoginViewTest.php

php phpunit.phar tests/SearchLogTest.php

php phpunit.phar tests/SqlControllerTest.php

php phpunit.phar tests/UploadImageTest.php

Individuele methoden testen:

php phpunit.phar --filter testSetupReturnsInstanceOfSqlController tests/SqlControllerTest.php

php phpunit.phar --filter testDatabaseConstants tests/SqlControllerTest.php

php phpunit.phar --filter testUploadImagesSuccess tests/UploadImageTest.php

php phpunit.phar --filter testUploadImagesNoFile tests/UploadImageTest.php

php phpunit.phar --filter testTranslation tests/LanguageTest.php

php phpunit.phar --filter testCreateUrl tests/LanguageTest.php

Alle testen tegelijk uitvoeren:

./vendor/bin/phpunit
