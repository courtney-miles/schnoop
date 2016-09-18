<?php

namespace MilesAsylum\Schnoop\PHPUnit\Framework;

use MilesAsylum\Schnoop\PHPUnit\MySQLTestHelper;
use PHPUnit\Framework\TestCase;

abstract class TestMySQLCase extends TestCase
{
    /**
     * @var \PDO
     */
    protected static $pdo;

    /**
     * @var MySQLTestHelper
     */
    protected static $mysqlHelper;

    public static function setUpBeforeClass()
    {
        self::$mysqlHelper = new MySQLTestHelper();

        self::$pdo = self::$mysqlHelper->getConnection();

        self::$mysqlHelper->raiseTestSchema();
    }

    public static function tearDownAfterClass()
    {
        self::$mysqlHelper = null;
        self::$pdo = null;
    }

    /**
     * @return \PDO
     */
    public function getConnection()
    {
        return self::$pdo;
    }

    public function getDatabaseName()
    {
        return self::$mysqlHelper->getDatabaseName();
    }

    public function getDatabaseUser()
    {
        return self::$mysqlHelper->getDatabaseUser();
    }

    public function getDatabaseHost()
    {
        return self::$mysqlHelper->getDatabaseHost();
    }
}
