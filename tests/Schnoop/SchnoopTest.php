<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 29/06/16
 * Time: 6:54 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop;

use MilesAsylum\Schnoop\DbInspector\DbInspectorInterface;
use MilesAsylum\Schnoop\Inspector\InspectorInterface;
use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\PHPUnit\Schnoop\MockPdo;
use MilesAsylum\Schnoop\SchemaAdapter\DatabaseAdapterInterface;
use MilesAsylum\Schnoop\SchemaFactory\SchemaBuilderInterface;
use MilesAsylum\Schnoop\Schnoop;
use MilesAsylum\SchnoopSchema\MySQL\Database\DatabaseInterface;
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
    }

    public function testHasDatabaseNot()
    {
        $this->assertFalse($this->schnoop->hasDatabase('bogus'));
    }

    public function testGetDatabase()
    {
        $fetchDb = $this->databaseList[0];
        $fetchDbReturn = array('Something');
        $mockDatabase = $this->createMock(DatabaseInterface::class);
        $mockDatabaseAdapter = $this->createMock(DatabaseAdapterInterface::class);

        $this->mockSchemaBuilder->expects($this->atLeastOnce())
            ->method('fetchDatabase')
            ->with($fetchDb)
            ->willReturn($mockDatabase);

        /** @var Schnoop|PHPUnit_Framework_MockObject_MockObject $schnoop */
        $schnoop = $this->getMockBuilder(Schnoop::class)
            ->setConstructorArgs(
                [
                    $this->mockInspector,
                    $this->mockSchemaBuilder
                ]
            )->setMethods(['createDatabaseAdapter'])
            ->getMock();
        $schnoop->method('createDatabaseAdapter')
            ->with($mockDatabase)
            ->willReturn($mockDatabaseAdapter);

        $this->assertSame($mockDatabaseAdapter, $schnoop->getDatabase($fetchDb));
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
        $fetchDb = $this->databaseList[0];
        $fetchTable = 'schnoop_table';
        $newTableReturn = 'Foo';

        $this->mockSchemaBuilder->expects($this->atLeastOnce())
            ->method('fetchTable')
            ->with($fetchDb, $fetchTable)
            ->willReturn($newTableReturn);

        $this->assertSame($newTableReturn, $this->schnoop->getTable($fetchDb, $fetchTable));
    }
}
