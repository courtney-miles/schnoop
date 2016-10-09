<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\Column\ColumnFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DatabaseFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Constraint\ForeignKeyFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Constraint\IndexFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\SchemaBuilder;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\TableFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\TriggerFactoryInterface;
use MilesAsylum\Schnoop\SchemaAdapter\MySQL\DatabaseInterface;
use MilesAsylum\Schnoop\SchemaAdapter\MySQL\TableInterface;
use MilesAsylum\Schnoop\Schnoop;
use MilesAsylum\SchnoopSchema\MySQL\Trigger\TriggerInterface;
use PHPUnit_Framework_MockObject_MockObject;

class SchemaBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SchemaBuilder
     */
    protected $schemaBuilder;

    /**
     * @var DatabaseFactoryInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockDatabaseMapper;

    /**
     * @var TableFactoryInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockTableMapper;

    /**
     * @var ColumnFactoryInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockColumnMapper;

    /**
     * @var IndexFactoryInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockIndexMapper;

    /**
     * @var ForeignKeyFactoryInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockForeignKeyMapper;

    /**
     * @var TriggerFactoryInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockTriggerMapper;

    /**
     * @var Schnoop|PHPUnit_Framework_MockObject_MockObject
     */
    private $mockSchnoop;

    public function setUp()
    {
        parent::setUp();

        $this->mockDatabaseMapper = $this->createMock(DatabaseFactoryInterface::class);
        $this->mockTableMapper = $this->createMock(TableFactoryInterface::class);
        $this->mockColumnMapper = $this->createMock(ColumnFactoryInterface::class);
        $this->mockIndexMapper = $this->createMock(IndexFactoryInterface::class);
        $this->mockForeignKeyMapper = $this->createMock(ForeignKeyFactoryInterface::class);
        $this->mockTriggerMapper = $this->createMock(TriggerFactoryInterface::class);
        $this->mockSchnoop = $this->createMock(Schnoop::class);

        $this->schemaBuilder = new SchemaBuilder(
            $this->mockDatabaseMapper,
            $this->mockTableMapper,
            $this->mockColumnMapper,
            $this->mockIndexMapper,
            $this->mockForeignKeyMapper,
            $this->mockTriggerMapper
        );

        $this->schemaBuilder->setSchnoop($this->mockSchnoop);
    }

    public function testFetchDatabase()
    {
        $databaseName = 'schnoop_db';
        $mockDatabase = $this->createMock(DatabaseInterface::class);
        $mockDatabase->expects($this->once())
            ->method('setSchnoop')
            ->with($this->mockSchnoop);

        $this->mockDatabaseMapper->expects($this->once())
            ->method('fetch')
            ->with($databaseName)
            ->willReturn($mockDatabase);

        $this->assertSame($mockDatabase, $this->schemaBuilder->fetchDatabase($databaseName));
    }

    public function testFetchTable()
    {
        $databaseName = 'schnoop_db';
        $tableName = 'schnoop_tbl';

        $columns = ['foo_columns'];
        $indexes = ['foo_indexes'];
        $foreignKeys = ['foo_foreign_keys'];

        $this->mockColumnMapper->expects($this->once())
            ->method('fetch')
            ->with($databaseName, $tableName)
            ->willReturn($columns);

        $this->mockIndexMapper->expects($this->once())
            ->method('fetch')
            ->with($databaseName, $tableName)
            ->willReturn($indexes);

        $this->mockForeignKeyMapper->expects($this->once())
            ->method('fetch')
            ->with($databaseName, $tableName)
            ->willReturn($foreignKeys);

        $mockTable = $this->createMock(TableInterface::class);
        $mockTable->expects($this->once())
            ->method('setColumns')
            ->with($columns);
        $mockTable->expects($this->once())
            ->method('setIndexes')
            ->with($indexes);
        $mockTable->expects($this->once())
            ->method('setForeignKeys')
            ->with($foreignKeys);
        $mockTable->expects($this->once())
            ->method('setSchnoop')
            ->with($this->mockSchnoop);

        $this->mockTableMapper->expects($this->once())
            ->method('fetch')
            ->with($databaseName, $tableName)
            ->willReturn($mockTable);

        $this->assertSame($mockTable, $this->schemaBuilder->fetchTable($databaseName, $tableName));
    }

    public function testFetchTriggers()
    {
        $databaseName = 'schnoop_db';
        $tableName = 'schnoop_tbl';

        $mockTriggers = [$this->createMock(TriggerInterface::class)];

        $this->mockTriggerMapper->expects($this->once())
            ->method('fetch')
            ->with($databaseName, $tableName)
            ->willReturn($mockTriggers);

        $this->assertSame($mockTriggers, $this->schemaBuilder->fetchTriggers($databaseName, $tableName));
    }
}
