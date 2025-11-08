<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Controller\SqlController;
use Resources\Config\Database;

require_once __DIR__ . '/../vendor/autoload.php';

class SqlControllerTest extends TestCase
{
    public function testSetupReturnsInstanceOfSqlController()
    {
        $sqlController = SqlController::setup();

        // Assertie om te controleren of het geretourneerde object een instantie is van SqlController
        $this->assertInstanceOf(SqlController::class, $sqlController);
    }
    public function testDatabaseConstants()
    {
        // Verkrijg de database configuratie uit Database klasse
        $dbHost = Database::DB_HOST;
        $dbName = Database::DB_NAME;
        $dbUser = Database::DB_USER;
        $dbPass = Database::DB_PASS;

        echo $dbHost;
        echo $dbName;
        echo $dbUser;
        echo $dbPass;

        // Maak een instantie van SqlController
        $sqlController = new SqlController();

        // Roep de private connectDatabase() methode aan via reflection
        $reflectionClass = new \ReflectionClass(SqlController::class);
        $connectMethod = $reflectionClass->getMethod('connectDatabase');
        $connectMethod->setAccessible(true); // Maak de methode toegankelijk voor tests

        // Gebruik try-catch om de connectDatabase() methode aan te roepen en de PDO-verbinding te controleren
        try {
            // Roep connectDatabase() aan
            $connectMethod->invoke($sqlController);

            // Haal de waarde van self::$pdo op
            $pdoProperty = $reflectionClass->getProperty('pdo');
            $pdoProperty->setAccessible(true);
            $pdoValue = $pdoProperty->getValue($sqlController);

            // Asserties om te controleren of self::$pdo een instantie is van PDO en of de juiste databaseconfiguratie wordt gebruikt
            $this->assertInstanceOf(\PDO::class, $pdoValue);
            // $this->assertEquals($dbHost, $pdoValue->getAttribute(\PDO::ATTR_SERVER_INFO));
            // $this->assertEquals(Database::DB_HOST, $pdoValue->getAttribute(\PDO::ATTR_SERVER_INFO));

        } catch (\PDOException $e) {
            // Vang eventuele PDO exceptions op voor foutafhandeling in tests
            $this->fail('PDOException: ' . $e->getMessage());
        }
    }
}