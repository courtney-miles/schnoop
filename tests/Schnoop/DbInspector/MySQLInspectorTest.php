<?php

namespace MilesAsylum\Schnoop\Tests\DbInspector;

use MilesAsylum\Schnoop\DbInspector\MySQLInspector;
use MilesAsylum\Schnoop\PHPUnit\Extensions\Database\TestMySQLCase;

class MySQLInspectorTest extends TestMySQLCase
{
    /**
     * @var MySQLInspector
     */
    protected $mysqlInspector;

    public function setUp()
    {
        $this->mysqlInspector = new MySQLInspector(self::$pdo);
    }

    public function testFetchDatabaseList()
    {
        $dbList = $this->mysqlInspector->fetchDatabaseList();

        $this->assertContains(self::$mysqlHelper->getDatabaseName(), $dbList);
    }

    public function testFetchDatabaseAttributes()
    {
        $expected = [
            'name' => self::$mysqlHelper->getDatabaseName(),
            'character_set_database' => 'utf8mb4',
            'collation_database' => 'utf8mb4_unicode_ci'
        ];
        
        $rawDatabase = $this->mysqlInspector->fetchDatabase(self::$mysqlHelper->getDatabaseName());

        $this->assertEquals($expected, $rawDatabase);
    }

    public function testFetchTableList()
    {
        $expected = ['schnoop_tbl'];
        
        $tableList = $this->mysqlInspector->fetchTableList(self::$mysqlHelper->getDatabaseName());

        $this->assertEquals($expected, $tableList);
    }

    public function testFetchTable()
    {
        $expected = [
            'name' => 'schnoop_tbl',
            'engine' => 'InnoDB',
            'row_format' => 'Compact',
            'collation' => 'utf8mb4_unicode_ci',
            'comment' => 'Theworks table comment.'
        ];
        
        $rawTable = $this->mysqlInspector->fetchTable(self::$mysqlHelper->getDatabaseName(), 'schnoop_tbl');
        
        $this->assertEquals($expected, $rawTable);
    }

    public function testFetchColumns()
    {
        $expected = [
            [
                'field' => 'id',
                'type' => 'int(10) unsigned',
                'collation' => null,
                'null' => 'NO',
                'default' => null,
                'extra' => 'auto_increment',
                'comment' => 'ID comment.'
            ]
        ];
        
        $rawColumns = $this->mysqlInspector->fetchColumns(self::$mysqlHelper->getDatabaseName(), 'schnoop_tbl');

        $this->assertEquals($expected, $rawColumns);
    }

    public function testFetchIndexes()
    {
        $expected = [
            [
                'key_name' => 'PRIMARY',
                'non_unique' => '0',
                'seq_in_index' => '1',
                'column_name' => 'id',
                'collation' => 'A',
                'index_type' => 'BTREE',
                'index_comment' => ''
            ]
        ];

        $rawIndexes = $this->mysqlInspector->fetchIndexes(self::$mysqlHelper->getDatabaseName(), 'schnoop_tbl');

        $this->assertEquals($expected, $rawIndexes);
    }

    public function testFetchTriggers()
    {
        $expected = [
            [
                'trigger' => 'schnoop_tbl_after_insert',
                'event' => 'INSERT',
                'timing' => 'AFTER',
                'sql_mode' => '',
                'definer' => self::$mysqlHelper->getConnectedUser(),
            ]
        ];

        $rawTriggers = $this->mysqlInspector->fetchTriggers(self::$mysqlHelper->getDatabaseName(), 'schnoop_tbl');

        foreach ($rawTriggers as $k => $rawTrigger) {
            $this->assertRegExp('/BEGIN.*END/s', $rawTrigger['statement']);
            unset($rawTriggers[$k]['statement']);
        }

        $this->assertEquals($expected, $rawTriggers);
    }

    public function testFetchFunctionList()
    {
        $expected = [
            'schnoop_func'
        ];

        $rawFuncList = $this->mysqlInspector->fetchFunctionList(self::$mysqlHelper->getDatabaseName());

        $this->assertEquals($expected, $rawFuncList);
    }

    public function testFetchFunction()
    {
        $expected = [
            'name' => 'schnoop_func',
            'definer' => self::$mysqlHelper->getConnectedUser(),
            'comment' => 'Schnoop function',
            'sql_mode' => '',
        ];

        $rawFunc = $this->mysqlInspector->fetchFunction(self::$mysqlHelper->getDatabaseName(), 'schnoop_func');

        $createFunction = $rawFunc['create_function'];
        unset($rawFunc['create_function']);

        $this->assertEquals($expected, $rawFunc);
        $this->assertRegExp('/^CREATE .* FUNCTION `?schnoop_func`?.*END$/s', $createFunction);
    }

    public function testFetchProcedureList()
    {
        $expected = [
            'schnoop_proc'
        ];

        $rawProcList = $this->mysqlInspector->fetchProcedureList(self::$mysqlHelper->getDatabaseName());

        $this->assertEquals($expected, $rawProcList);
    }

    public function testFetchProcedure()
    {
        $expected = [
            'name' => 'schnoop_proc',
            'definer' => self::$mysqlHelper->getConnectedUser(),
            'comment' => 'Schnoop procedure',
            'sql_mode' => '',
        ];

        $rawProc = $this->mysqlInspector->fetchProcedure(self::$mysqlHelper->getDatabaseName(), 'schnoop_proc');

        $createProcedure = $rawProc['create_procedure'];
        unset($rawProc['create_procedure']);

        $this->assertEquals($expected, $rawProc);
        $this->assertRegExp('/^CREATE .* PROCEDURE `?schnoop_proc`?.*END$/s', $createProcedure);
    }

    public function testRestoreDatabaseUseWhenFetchingDatabase()
    {
        $this->markTestIncomplete(
            'Have not tested that the previously selected database is restored when fetching another database.'
        );
    }
}
