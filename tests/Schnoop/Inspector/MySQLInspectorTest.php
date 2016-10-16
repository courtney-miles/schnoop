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

    protected $triggerName = 'schnoop_tbl_ia_trig';

    protected $functionName = 'schnoop_func';

    protected $procedureName = 'schnoop_proc';

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

        $this->getConnection()->exec(<<<SQL
DROP TRIGGER IF EXISTS `{$this->triggerName}`
SQL
        );
        $this->getConnection()->exec(<<<SQL
CREATE TRIGGER `{$this->triggerName}` AFTER INSERT
ON `{$this->tableName}` FOR EACH ROW
BEGIN
END
SQL
        );

        $this->getConnection()->exec(<<<SQL
DROP FUNCTION IF EXISTS `{$this->functionName}`
SQL
        );
        $this->getConnection()->exec(<<<SQL
CREATE FUNCTION `{$this->functionName}` ()
RETURNS INTEGER 
RETURN 1
SQL
        );

        $this->getConnection()->exec(<<<SQL
DROP PROCEDURE IF EXISTS `{$this->procedureName}`
SQL
        );
        $this->getConnection()->exec(<<<SQL
CREATE PROCEDURE `{$this->procedureName}` ()
BEGIN
END
SQL
        );

        $this->mySQLInspector = new MySQLInspector($this->getConnection());
    }

    public function testGetPDO()
    {
        $this->assertSame($this->getConnection(), $this->mySQLInspector->getPDO());
    }

    public function testFetchDatabaseList()
    {
        $this->assertContains($this->getDatabaseName(), $this->mySQLInspector->fetchDatabaseList());
    }

    public function testFetchTableList()
    {
        $this->assertSame([$this->tableName], $this->mySQLInspector->fetchTableList($this->getDatabaseName()));
    }

    public function testFetchTriggersForTable()
    {
        $this->assertSame(
            [$this->triggerName],
            $this->mySQLInspector->fetchTriggerList($this->getDatabaseName(), $this->tableName)
        );
    }

    public function testFetchActiveDatabase()
    {
        $this->assertSame($this->getDatabaseName(), $this->mySQLInspector->fetchActiveDatabase());
    }

    public function testFetchFunctionList()
    {
        $this->assertSame(
            [$this->functionName],
            $this->mySQLInspector->fetchFunctionList($this->getDatabaseName())
        );
    }

    public function testFetchProcedureList()
    {
        $this->assertSame(
            [$this->procedureName],
            $this->mySQLInspector->fetchProcedureList($this->getDatabaseName())
        );
    }
}
