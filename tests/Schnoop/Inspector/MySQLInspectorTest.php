<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\Inspector;

use MilesAsylum\Schnoop\Inspector\MySQLInspector;
use MilesAsylum\Schnoop\PHPUnit\Framework\TestMySQLCase;

class MySQLInspectorTest extends TestMySQLCase
{
    /**
     * @var MySQLInspector
     */
    protected $mySQLInspector;

    protected $tableName = 'schnoop_tbl';

    public function setUp()
    {
        parent::setUp();

        $this->getConnection()->exec(<<<SQL
USE {$this->getDatabaseName()}
SQL
        );
        $this->getConnection()->exec(<<<SQL
DROP TABLE IF EXISTS `{$this->tableName}`
SQL
        );
        $this->getConnection()->exec(<<<SQL
CREATE TABLE `{$this->tableName}` (
  id INTEGER
)
SQL
        );

        $this->mySQLInspector = new MySQLInspector($this->getConnection());
    }

    public function testFetchDatabaseList()
    {
        $this->assertContains($this->getDatabaseName(), $this->mySQLInspector->fetchDatabaseList());
    }

    public function testFetchTableList()
    {
        $this->assertSame([$this->tableName], $this->mySQLInspector->fetchTableList($this->getDatabaseName()));
    }

    public function testFetchActiveDatabase()
    {
        $this->assertSame($this->getDatabaseName(), $this->mySQLInspector->fetchActiveDatabase());
    }
}
