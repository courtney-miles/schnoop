<?php

namespace MilesAsylum\Schnoop\PHPUnit;

class SetupTestDbSchema
{
    public static function setup()
    {
        if ($_ENV['TESTS_SCHNOOP_DBADAPTER_MYSQL_ENABLED']) {
            self::setupOnMysql();
        }
    }

    public static function setupOnMysql()
    {
        $conn = self::getMySQLConnection();
        self::createMySQLDatabase($conn);
    }

    protected static function getMySQLConnection()
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%d',
            $_ENV['TESTS_SCHNOOP_DBADAPTER_MYSQL_HOST'],
            !empty($_ENV['TESTS_SCHNOOP_DBADAPTER_MYSQL_PORT']) ? $_ENV['TESTS_SCHNOOP_DBADAPTER_MYSQL_PORT'] : 3306
        );

        $pdo = new \PDO(
            $dsn,
            $_ENV['TESTS_SCHNOOP_DBADAPTER_MYSQL_USERNAME'],
            $_ENV['TESTS_SCHNOOP_DBADAPTER_MYSQL_PASSWORD']
        );
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }

    protected static function createMySQLDatabase(\PDO $conn)
    {
        $database = $_ENV['TESTS_SCHNOOP_DBADAPTER_MYSQL_DATABASE'];

        $conn->query(<<< SQL
DROP SCHEMA IF EXISTS `$database`
SQL
        );

        $conn->query(<<< SQL
CREATE DATABASE `$database` CHARACTER SET utf8mb4 COLLATE 'utf8mb4_unicode_ci'
SQL
        );

        $conn->query(<<< SQL
CREATE TABLE `$database`.`theworks` (
  id INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL COMMENT 'ID comment.'
) ENGINE 'InnoDB' COMMENT 'Theworks table comment.'
SQL
        );

        $conn->query(<<< SQL
CREATE DEFINER=CURRENT_USER TRIGGER `$database`.`theworks_ia` AFTER INSERT ON `theworks` FOR EACH ROW BEGIN

END;
SQL
        );
    }
}
