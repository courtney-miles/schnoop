<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 29/06/16
 * Time: 6:54 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop;

use MilesAsylum\Schnoop\Inspector\InspectorInterface;
use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\PHPUnit\Schnoop\MockPdo;
use MilesAsylum\Schnoop\SchemaAdapter\MySQL\Database;
use MilesAsylum\Schnoop\SchemaAdapter\MySQL\DatabaseInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\SchemaBuilderInterface;
use MilesAsylum\Schnoop\Schnoop;
use PHPUnit_Framework_MockObject_MockObject;

class SchnoopTest extends SchnoopTestCase
{
    /**
     * @var Schnoop
     */
    protected $schnoop;
    
    protected $databaseList = [
        'schnoop_db_one',
        'schnoop_db_two'
    ];

    /**
     * @var InspectorInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockInspector;

    /**
     * @var SchemaBuilderInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockSchemaBuilder;

    public function setUp()
    {
        parent::setUp();
        
        /** @var \PDO $mockPdo */
        $mockPdo = $this->createMock(MockPdo::class);
        
        $this->mockInspector = $this->createMock(InspectorInterface::class);
        $this->mockInspector->method('fetchDatabaseList')
            ->willReturn($this->databaseList);

        $this->mockSchemaBuilder = $this->createMock(SchemaBuilderInterface::class);

        $this->schnoop = new Schnoop(
            $this->mockInspector,
            $this->mockSchemaBuilder
        );
    }

    public function testDatabaseList()
    {
        $this->assertSame(array_values($this->databaseList), $this->schnoop->getDatabaseList());
    }

    public function testHasDatabase()
    {
        $this->assertTrue($this->schnoop->hasDatabase('schnoop_db_one'));
        $this->assertFalse($this->schnoop->hasDatabase('bogus'));
    }

    public function testGetDatabase()
    {
        $fetchDb = $this->databaseList[0];
        $mockDatabase = $this->createMock(DatabaseInterface::class);

        $this->mockSchemaBuilder->expects($this->atLeastOnce())
            ->method('fetchDatabase')
            ->with($fetchDb)
            ->willReturn($mockDatabase);

        $this->assertSame($mockDatabase, $this->schnoop->getDatabase($fetchDb));
    }

    public function testGetActiveDatabase()
    {
        $activeDatabaseName = $this->databaseList[0];

        $mockDatabase = $this->createMock(DatabaseInterface::class);

        $this->setActiveDatabase($activeDatabaseName);

        $this->mockSchemaBuilder->expects($this->atLeastOnce())
            ->method('fetchDatabase')
            ->with($activeDatabaseName)
            ->willReturn($mockDatabase);

        $this->assertSame($mockDatabase, $this->schnoop->getDatabase());
    }

    public function testGetNonExistentDatabase()
    {
        $this->assertNull($this->schnoop->getDatabase('bogus_db'));
    }

    public function testGetTableList()
    {
        $fetchDb = $this->databaseList[0];
        $fetchDbReturn = 'Foo';

        $this->mockInspector->expects($this->atLeastOnce())
            ->method('fetchTableList')
            ->with($fetchDb)
            ->willReturn($fetchDbReturn);

        $this->assertSame($fetchDbReturn, $this->schnoop->getTableList($fetchDb));
    }

    public function testGetTable()
    {
        $databaseName = $this->databaseList[0];
        $tableName = 'schnoop_table';
        $expectedTable = '__table__';

        $this->setActiveDatabase($databaseName);

        $this->mockSchemaBuilder->expects($this->atLeastOnce())
            ->method('fetchTable')
            ->with($tableName, $databaseName)
            ->willReturn($expectedTable);

        $this->assertSame($expectedTable, $this->schnoop->getTable($tableName, $databaseName));
        $this->assertSame($expectedTable, $this->schnoop->getTable($tableName));
    }

    public function testGetNonExistentTable()
    {
        $databaseName = $this->databaseList[0];
        $tableName = 'bogus_table';

        $this->setActiveDatabase($databaseName);

        $this->mockSchemaBuilder->expects($this->atLeastOnce())
            ->method('fetchTable')
            ->with($tableName, $databaseName)
            ->willReturn(null);

        $this->assertNull($this->schnoop->getTable($tableName, $databaseName));
        $this->assertNull($this->schnoop->getTable($tableName));
    }

    public function testHasTable()
    {
        $tableName = 'schnoop_tbl';
        $databaseName = $this->databaseList[0];

        $this->mockInspector->method('fetchTableList')
            ->with($databaseName)
            ->willReturn([$tableName]);

        $this->assertTrue($this->schnoop->hasTable($tableName, $databaseName));
        $this->assertFalse($this->schnoop->hasTable('bogus_tbl', $databaseName));
    }

    public function testHasTriggers()
    {
        $tableName = 'schnoop_tbl';
        $databaseName = $this->databaseList[0];

        $triggers = ['__tiggers__'];

        $this->setActiveDatabase($databaseName);

        $this->mockInspector->method('fetchTableList')
            ->with($databaseName)
            ->willReturn([$tableName]);

        $this->mockInspector->method('fetchTriggerList')
            ->with($databaseName, $tableName)
            ->willReturn($triggers);

        $this->assertTrue($this->schnoop->hasTriggers($tableName, $databaseName));
        $this->assertTrue($this->schnoop->hasTriggers($tableName));
    }

    public function testGetTriggers()
    {
        $tableName = 'schnoop_tbl';
        $databaseName = $this->databaseList[0];

        $triggers = ['__tiggers__'];

        $this->setActiveDatabase($databaseName);

        $this->mockInspector->method('fetchTableList')
            ->with($databaseName)
            ->willReturn([$tableName]);

        $this->mockSchemaBuilder->method('fetchTriggers')
            ->with($tableName, $databaseName)
            ->willReturn($triggers);

        $this->assertSame($triggers, $this->schnoop->getTriggers($tableName, $databaseName));
        $this->assertSame($triggers, $this->schnoop->getTriggers($tableName));
    }

    public function testHasFunction()
    {
        $functionName = 'schnoop_func';
        $databaseName = $this->databaseList[0];

        $this->setActiveDatabase($databaseName);

        $this->mockInspector->method('fetchFunctionList')
            ->with($databaseName)
            ->willReturn([$functionName]);

        $this->assertTrue($this->schnoop->hasFunction($functionName, $databaseName));
        $this->assertTrue($this->schnoop->hasFunction($functionName));
        $this->assertFalse($this->schnoop->hasFunction('bogus_func', $databaseName));
    }

    public function testGetFunction()
    {
        $functionName = 'schnoop_func';
        $databaseName = $this->databaseList[0];
        $function = '__function__';

        $this->setActiveDatabase($databaseName);

        $this->mockSchemaBuilder->method('fetchFunction')
            ->with($functionName, $databaseName)
            ->willReturn($function);

        $this->assertSame($function, $this->schnoop->getFunction($functionName, $databaseName));
        $this->assertSame($function, $this->schnoop->getFunction($functionName));
    }

    public function testGetFunctionNotExists()
    {
        $bogusFuncName = 'bogus_func';
        $databaseName = $this->databaseList[0];

        $this->setActiveDatabase($databaseName);

        $this->mockSchemaBuilder->method('fetchFunction')
            ->with($bogusFuncName, $databaseName)
            ->willReturn(null);

        $this->assertNull($this->schnoop->getFunction($bogusFuncName, $databaseName));
    }

    public function testHasProcedure()
    {
        $procedureName = 'schnoop_proc';
        $databaseName = $this->databaseList[0];

        $this->setActiveDatabase($databaseName);

        $this->mockInspector->method('fetchProcedureList')
            ->with($databaseName)
            ->willReturn([$procedureName]);

        $this->assertTrue($this->schnoop->hasProcedure($procedureName, $databaseName));
        $this->assertTrue($this->schnoop->hasProcedure($procedureName));
        $this->assertFalse($this->schnoop->hasProcedure('bogus_proc', $databaseName));
    }

    public function testGetProcedure()
    {
        $procedureName = 'schnoop_proc';
        $databaseName = $this->databaseList[0];
        $procedure = '__procedure__';

        $this->setActiveDatabase($databaseName);

        $this->mockSchemaBuilder->method('fetchProcedure')
            ->with($procedureName, $databaseName)
            ->willReturn($procedure);

        $this->assertSame($procedure, $this->schnoop->getProcedure($procedureName, $databaseName));
        $this->assertSame($procedure, $this->schnoop->getProcedure($procedureName));
    }

    public function testGetProcedureNotExists()
    {
        $bogusProcName = 'bogus_proc';
        $databaseName = $this->databaseList[0];

        $this->setActiveDatabase($databaseName);

        $this->mockSchemaBuilder->method('fetchProcedure')
            ->with($bogusProcName, $databaseName)
            ->willReturn(null);

        $this->assertNull($this->schnoop->getProcedure($bogusProcName, $databaseName));
    }

    public function testCreateSelf()
    {
        $mockPdo = $this->createMock(MockPdo::class);

        $this->assertInstanceOf(Schnoop::class, Schnoop::createSelf($mockPdo));
    }

    /**
     * @dataProvider getMethodCallTriggersExceptionForBogusDatabaseData
     * @expectedException \MilesAsylum\Schnoop\Exception\SchnoopException
     * @expectedExceptionMessage A database named 'bogus_db' does not exist.
     * @param string $method
     * @param array $params
     */
    public function testMethodCallTriggersExceptionForBogusDatabase($method, array $params = [])
    {
        $this->schnoop->$method(...$params);
    }

    /**
     * @see testMethodCallTriggersExceptionForBogusDatabase
     * @return array
     */
    public function getMethodCallTriggersExceptionForBogusDatabaseData()
    {
        return [
            [
                'hasTable',
                ['schnoop_tbl', 'bogus_db']
            ],
            [
                'getTable',
                ['schnoop_tbl', 'bogus_db']
            ],
            [
                'hasTriggers',
                ['schnoop_tbl', 'bogus_db']
            ],
            [
                'getTriggers',
                ['schnoop_tbl', 'bogus_db']
            ],
            [
                'hasFunction',
                ['schnoop_func', 'bogus_db']
            ],
            [
                'getFunction',
                ['schnoop_func', 'bogus_db']
            ],
            [
                'hasProcedure',
                ['schnoop_proc', 'bogus_db']
            ],
            [
                'getProcedure',
                ['schnoop_proc', 'bogus_db']
            ]
        ];
    }

    /**
     * @dataProvider getMethodCallTriggersExceptionWithoutActiveDatabaseData
     * @expectedException \MilesAsylum\Schnoop\Exception\SchnoopException
     * @expectedExceptionMessage Database not specified and an active database has not been set.
     * @param string $method
     * @param array $params
     */
    public function testMethodCallTriggersExceptionWithoutActiveDatabase($method, array $params = [])
    {
        $this->schnoop->$method(...$params);
    }

    /**
     * @see testMethodCallTriggersExceptionWithoutActiveDatabase
     * @return array
     */
    public function getMethodCallTriggersExceptionWithoutActiveDatabaseData()
    {
        return [
            [
                'hasTable',
                ['schnoop_tbl']
            ],
            [
                'getTable',
                ['schnoop_tbl']
            ],
            [
                'hasTriggers',
                ['schnoop_tbl']
            ],
            [
                'getTriggers',
                ['schnoop_tbl']
            ],
            [
                'hasFunction',
                ['schnoop_func']
            ],
            [
                'getFunction',
                ['schnoop_func']
            ],
            [
                'hasProcedure',
                ['schnoop_proc']
            ],
            [
                'getProcedure',
                ['schnoop_proc']
            ]
        ];
    }

    /**
     * @dataProvider getMethodTriggersExceptionForBogusTableData
     * @expectedException \MilesAsylum\Schnoop\Exception\SchnoopException
     * @expectedExceptionMessage A table named 'bogus_tbl' does not exist in database 'schnoop_db_one'.
     * @param string $method
     * @param string $validDatabaseName
     * @param array $params
     */
    public function testMethodTriggersExceptionForBogusTable($method, $validDatabaseName, array $params = [])
    {
        $this->mockInspector->method('fetchTableList')
            ->with($validDatabaseName)
            ->willReturn([]);

        $this->schnoop->$method(...$params);
    }

    /**
     * @see testMethodTriggersExceptionForBogusTable
     * @return array
     */
    public function getMethodTriggersExceptionForBogusTableData()
    {
        $databaseName = $this->databaseList[0];

        return [
            [
                'hasTriggers',
                $databaseName,
                ['bogus_tbl', $databaseName]
            ],
            [
                'getTriggers',
                $databaseName,
                ['bogus_tbl', $databaseName]
            ],
        ];
    }

    protected function setActiveDatabase($databaseName)
    {
        $this->mockInspector->method('fetchActiveDatabase')
            ->willReturn($databaseName);
    }
}
