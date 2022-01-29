<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\DatabaseInterface;
use MilesAsylum\Schnoop\SchemaAdapter\MySQL\RoutineFunction;
use MilesAsylum\Schnoop\SchemaAdapter\MySQL\RoutineProcedure;
use MilesAsylum\Schnoop\SchemaAdapter\MySQL\TableInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Column\ColumnFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Constraint\ForeignKeyFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Constraint\IndexFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DatabaseFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\FunctionFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\ProcedureFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\SchemaBuilder;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\TableFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\TriggerFactoryInterface;
use MilesAsylum\Schnoop\Schnoop;
use MilesAsylum\SchnoopSchema\MySQL\Trigger\TriggerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SchemaBuilderTest extends TestCase
{
    /**
     * @var SchemaBuilder
     */
    protected $schemaBuilder;

    /**
     * @var DatabaseFactoryInterface|MockObject
     */
    protected $mockDatabaseFactory;

    /**
     * @var TableFactoryInterface|MockObject
     */
    protected $mockTableFactory;

    /**
     * @var ColumnFactoryInterface|MockObject
     */
    protected $mockColumnFactory;

    /**
     * @var IndexFactoryInterface|MockObject
     */
    protected $mockIndexFactory;

    /**
     * @var ForeignKeyFactoryInterface|MockObject
     */
    protected $mockForeignKeyFactory;

    /**
     * @var TriggerFactoryInterface|MockObject
     */
    protected $mockTriggerFactory;

    /**
     * @var FunctionFactoryInterface|MockObject
     */
    protected $mockFunctionFactory;

    /**
     * @var ProcedureFactoryInterface|MockObject
     */
    protected $mockProcedureFactory;

    /**
     * @var Schnoop|MockObject
     */
    private $mockSchnoop;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockDatabaseFactory = $this->createMock(DatabaseFactoryInterface::class);
        $this->mockTableFactory = $this->createMock(TableFactoryInterface::class);
        $this->mockColumnFactory = $this->createMock(ColumnFactoryInterface::class);
        $this->mockIndexFactory = $this->createMock(IndexFactoryInterface::class);
        $this->mockForeignKeyFactory = $this->createMock(ForeignKeyFactoryInterface::class);
        $this->mockTriggerFactory = $this->createMock(TriggerFactoryInterface::class);
        $this->mockFunctionFactory = $this->createMock(FunctionFactoryInterface::class);
        $this->mockProcedureFactory = $this->createMock(ProcedureFactoryInterface::class);
        $this->mockSchnoop = $this->createMock(Schnoop::class);

        $this->schemaBuilder = new SchemaBuilder(
            $this->mockDatabaseFactory,
            $this->mockTableFactory,
            $this->mockColumnFactory,
            $this->mockIndexFactory,
            $this->mockForeignKeyFactory,
            $this->mockTriggerFactory,
            $this->mockFunctionFactory,
            $this->mockProcedureFactory
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

        $this->mockDatabaseFactory->expects($this->once())
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

        $this->mockColumnFactory->expects($this->once())
            ->method('fetch')
            ->with($tableName, $databaseName)
            ->willReturn($columns);

        $this->mockIndexFactory->expects($this->once())
            ->method('fetch')
            ->with($tableName, $databaseName)
            ->willReturn($indexes);

        $this->mockForeignKeyFactory->expects($this->once())
            ->method('fetch')
            ->with($tableName, $databaseName)
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

        $this->mockTableFactory->expects($this->once())
            ->method('fetch')
            ->with($tableName, $databaseName)
            ->willReturn($mockTable);

        $this->assertSame($mockTable, $this->schemaBuilder->fetchTable($tableName, $databaseName));
    }

    public function testFetchTriggers()
    {
        $databaseName = 'schnoop_db';
        $tableName = 'schnoop_tbl';

        $mockTriggers = [$this->createMock(TriggerInterface::class)];

        $this->mockTriggerFactory->expects($this->once())
            ->method('fetch')
            ->with($tableName, $databaseName)
            ->willReturn($mockTriggers);

        $this->assertSame($mockTriggers, $this->schemaBuilder->fetchTriggers($tableName, $databaseName));
    }

    public function testFetchFunction()
    {
        $databaseName = 'schnoop_db';
        $functionName = 'schnoop_func';

        $mockFunction = $this->createMock(RoutineFunction::class);

        $this->mockFunctionFactory->expects($this->once())
            ->method('fetch')
            ->with($functionName, $databaseName)
            ->willReturn($mockFunction);

        $this->assertSame($mockFunction, $this->schemaBuilder->fetchFunction($functionName, $databaseName));
    }

    public function testFetchProcedure()
    {
        $databaseName = 'schnoop_db';
        $procedureName = 'schnoop_proc';

        $mockProcedure = $this->createMock(RoutineProcedure::class);

        $this->mockProcedureFactory->expects($this->once())
            ->method('fetch')
            ->with($procedureName, $databaseName)
            ->willReturn($mockProcedure);

        $this->assertSame($mockProcedure, $this->schemaBuilder->fetchProcedure($procedureName, $databaseName));
    }
}
