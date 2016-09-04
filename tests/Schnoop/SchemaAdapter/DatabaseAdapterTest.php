<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaAdapter;

use MilesAsylum\Schnoop\SchemaAdapter\DatabaseAdapter;
use MilesAsylum\Schnoop\Schnoop;
use MilesAsylum\SchnoopSchema\MySQL\Database\DatabaseInterface;
use MilesAsylum\SchnoopSchema\MySQL\Table\TableInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class DatabaseAdapterTest extends TestCase
{
    /**
     * @var DatabaseAdapter
     */
    protected $databaseAdapter;

    /**
     * @var DatabaseInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockDatabase;

    /**
     * @var Schnoop|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockSchnoop;

    public function setUp()
    {
        parent::setUp();

        $this->mockDatabase = $this->createMock(DatabaseInterface::class);
        $this->mockSchnoop = $this->createMock(Schnoop::class);

        $this->databaseAdapter = new DatabaseAdapter($this->mockDatabase, $this->mockSchnoop);
    }

    public function testGetName()
    {
        $databaseName = 'schnoop_db';
        $this->mockDatabase->expects($this->once())
            ->method('getName')
            ->willReturn($databaseName);

        $this->assertSame($databaseName, $this->databaseAdapter->getName());
    }

    public function testGetDefaultCollation()
    {
        $defaultCollation = 'utf8mb4_general_ci';
        $this->mockDatabase->expects($this->once())
            ->method('getDefaultCollation')
            ->willReturn($defaultCollation);

        $this->assertSame($defaultCollation, $this->databaseAdapter->getDefaultCollation());
    }

    public function testHasDefaultCollation()
    {
        $hasCollation = true;
        $this->mockDatabase->expects($this->once())
            ->method('hasDefaultCollation')
            ->willReturn($hasCollation);

        $this->assertSame($hasCollation, $this->databaseAdapter->hasDefaultCollation());
    }

    public function testSetDefaultCollation()
    {
        $defaultCollation = 'utf8mb4_general_ci';
        $this->mockDatabase->expects($this->once())
            ->method('setDefaultCollation')
            ->willReturn($defaultCollation);

        $this->databaseAdapter->setDefaultCollation($defaultCollation);
    }

    public function testGetTableList()
    {
        $tableList = ['schnoop_tbl'];

        $this->mockSchnoop->expects($this->once())
            ->method('getTableList')
            ->willReturn($tableList);

        $this->assertSame($tableList, $this->databaseAdapter->getTableList());
    }

    public function testGetTable()
    {
        $databaseName = 'schnoop_db';
        $tableName = 'schnoop_tbl';

        $mockTable = $this->createMock(TableInterface::class);

        $this->mockDatabase->expects($this->once())
            ->method('getName')
            ->willReturn($databaseName);

        $this->mockSchnoop->expects($this->once())
            ->method('getTable')
            ->with($databaseName, $tableName)
            ->willReturn($mockTable);

        $this->assertSame($mockTable, $this->databaseAdapter->getTable($tableName));
    }

    public function testCastToString()
    {
        $asString = 'foo';

        $this->mockDatabase->expects($this->once())
            ->method('__toString')
            ->willReturn($asString);

        $this->assertSame($asString, (string)$this->databaseAdapter);
    }
}
