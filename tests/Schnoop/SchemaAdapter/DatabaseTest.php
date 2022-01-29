<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaAdapter;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\Database;
use MilesAsylum\Schnoop\Schnoop;
use MilesAsylum\SchnoopSchema\MySQL\Table\TableInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    protected $name = 'schnoop_db';

    /**
     * @var Database
     */
    protected $database;

    /**
     * @var Schnoop|MockObject
     */
    protected $mockSchnoop;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockSchnoop = $this->createMock(Schnoop::class);

        $this->database = new Database($this->name);
        $this->database->setSchnoop($this->mockSchnoop);
    }

    public function testGetTableList()
    {
        $tableList = ['schnoop_tbl'];

        $this->mockSchnoop->expects($this->once())
            ->method('getTableList')
            ->with($this->name)
            ->willReturn($tableList);

        $this->assertSame($tableList, $this->database->getTableList());
    }

    public function testGetTable()
    {
        $tableName = 'schnoop_tbl';

        $mockTable = $this->createMock(TableInterface::class);

        $this->mockSchnoop->expects($this->once())
            ->method('getTable')
            ->with($tableName, $this->name)
            ->willReturn($mockTable);

        $this->assertSame($mockTable, $this->database->getTable($tableName));
    }

    public function testHasTable()
    {
        $tableName = 'schnoop_tbl';

        $this->mockSchnoop->expects($this->once())
            ->method('hasTable')
            ->with($tableName, $this->name)
            ->willReturn(true);

        $this->assertTrue($this->database->hasTable($tableName));
    }
}
