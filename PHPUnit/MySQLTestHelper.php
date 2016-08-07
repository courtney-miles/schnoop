<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 5/06/16
 * Time: 4:32 PM
 */

namespace MilesAsylum\Schnoop\PHPUnit;

use PDO;

class MySQLTestHelper
{
    /**
     * @var PDO
     */
    protected $pdo;

    public function __construct()
    {
        $this->pdo = $this->connect($this->makeDsn());
    }

    /**
     * @return PDO
     */
    public function getConnection()
    {
        return $this->pdo;
    }

    public function getDatabaseName()
    {
        return $_ENV['TESTS_SCHNOOP_DBADAPTER_MYSQL_DATABASE'];
    }

    public function getDatabaseUser()
    {
        return $_ENV['TESTS_SCHNOOP_DBADAPTER_MYSQL_USERNAME'];
    }

    public function getDatabasePassword()
    {
        return $_ENV['TESTS_SCHNOOP_DBADAPTER_MYSQL_PASSWORD'];
    }

    public function getConnectedUser()
    {
        return $this->pdo->query('SELECT USER()')->fetchColumn();
    }

    public function raiseTestSchema()
    {
        $database = $_ENV['TESTS_SCHNOOP_DBADAPTER_MYSQL_DATABASE'];

        $this->pdo->query(<<< SQL
DROP SCHEMA IF EXISTS `$database`
SQL
        );

        $this->pdo->query(<<< SQL
CREATE DATABASE `$database` CHARACTER SET utf8mb4 COLLATE 'utf8mb4_unicode_ci'
SQL
        );

        $this->pdo->query(<<< SQL
CREATE TABLE `$database`.`schnoop_tbl` (
  id INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL COMMENT 'ID comment.'
) ENGINE 'InnoDB' COMMENT 'Theworks table comment.'
SQL
        );

        $this->pdo->query(<<< SQL
CREATE DEFINER=CURRENT_USER TRIGGER `$database`.`schnoop_tbl_after_insert` AFTER INSERT ON `schnoop_tbl` FOR EACH ROW BEGIN
  DECLARE x INT;
  SELECT 1 INTO x;
END;
SQL
        );
            $this->pdo->query(<<< SQL
CREATE DEFINER=CURRENT_USER PROCEDURE `$database`.`schnoop_proc`()
    MODIFIES SQL DATA
    DETERMINISTIC
    COMMENT 'Schnoop procedure'
BEGIN
END
SQL
        );
        $this->pdo->query(<<< SQL
CREATE DEFINER=CURRENT_USER FUNCTION `$database`.`schnoop_func`() RETURNS VARCHAR(255)
    DETERMINISTIC
    COMMENT 'Schnoop function'
BEGIN
  RETURN 'Schnoop';
END
SQL
        );
    }
    
    protected function makeDsn()
    {
        $dsn = sprintf(
            "mysql:host=%s;port=%d",
            $_ENV['TESTS_SCHNOOP_DBADAPTER_MYSQL_HOST'],
            !empty($_ENV['TESTS_SCHNOOP_DBADAPTER_MYSQL_PORT']) ? $_ENV['TESTS_SCHNOOP_DBADAPTER_MYSQL_PORT'] : 3306
        );

        return $dsn;
    }
    
    protected function connect($dsn)
    {
        $pdo = new \PDO($dsn, $this->getDatabaseUser(), $this->getDatabasePassword());
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $pdo;
    }
}