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

    protected static $mysqlVersion;

    /**
     * @var string the SQL Mode used for the connection
     */
    protected $sqlMode = 'STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION';

    public static function setUpBeforeClass(): void
    {
        self::$mysqlHelper = new MySQLTestHelper();

        self::$pdo = self::$mysqlHelper->getConnection();

        self::$mysqlVersion = self::$pdo->query(
            'SHOW VARIABLES WHERE Variable_name = \'version\''
        )->fetchColumn(1);

        self::$mysqlHelper->raiseTestSchema();
    }

    public static function tearDownAfterClass(): void
    {
        self::$mysqlHelper = null;
        self::$pdo = null;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->getConnection()->prepare(<<<SQL
SET SESSION sql_mode=?
SQL
        )->execute([$this->sqlMode]);
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

    protected static function isMySql8(): bool
    {
        return strpos(self::$mysqlVersion, '8.') === 0;
    }
}
