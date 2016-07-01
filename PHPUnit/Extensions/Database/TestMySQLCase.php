<?php

namespace MilesAsylum\Schnoop\PHPUnit\Extensions\Database;

use MilesAsylum\Schnoop\PHPUnit\MySQLTestHelper;
use PDO;
use PHPUnit_Extensions_Database_DataSet_IDataSet;
use PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection;
use PHPUnit_Extensions_Database_DB_IDatabaseConnection;

abstract class TestMySQLCase extends \PHPUnit_Extensions_Database_TestCase
{
    /**
     * @var \PDO
     */
    static protected $pdo;

    /**
     * @var \MilesAsylum\Schnoop\PHPUnit\MySQLTestHelper
     */
    protected static $mysqlHelper;

    /**
     * @var PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection
     */
    private $conn;

    public static function setUpBeforeClass()
    {
        if (empty($_ENV['TESTS_SCHNOOP_DBADAPTER_MYSQL_ENABLED'])) {
            self::markTestSkipped('Mysql tests disabled. See TESTS_SCHNOOP_DBADAPTER_MYSQL_ENABLED constant.');
        }
        
        self::$mysqlHelper = new MySQLTestHelper();

        self::$pdo = self::$mysqlHelper->getConnection();

        self::$mysqlHelper->raiseTestSchema();
    }

    public static function tearDownAfterClass()
    {
        self::$mysqlHelper = null;
        self::$pdo = null;
    }

    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    public function getConnection()
    {
        if (!isset($this->conn)) {
            $this->conn = $this->createDefaultDBConnection(
                self::$pdo,
                $_ENV['TESTS_SCHNOOP_DBADAPTER_MYSQL_DATABASE']
            );
        }

        return $this->conn;
    }

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet()
    {
        $refObj = new \ReflectionObject($this);
        $classFileName = $refObj->getFileName();
        $classDirectory = pathinfo($classFileName, PATHINFO_DIRNAME);

        return $this->createFlatXMLDataSet($classDirectory . '/_fixtures/' . $refObj->getShortName() . '.xml');
    }
}