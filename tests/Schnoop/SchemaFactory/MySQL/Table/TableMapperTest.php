<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\Table;

use MilesAsylum\Schnoop\PHPUnit\Framework\TestMySQLCase;
use MilesAsylum\Schnoop\PHPUnit\Schnoop\MockPdo;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Table\TableMapper;
use MilesAsylum\SchnoopSchema\MySQL\Table\Table;
use PHPUnit_Framework_MockObject_MockObject;

class TableMapperTest extends TestMySQLCase
{
    /**
     * @var TableMapper
     */
    protected $tableMapper;

    protected $tableName;

    protected $databaseName;

    public function setUp()
    {
        parent::setUp();

        $this->tableName = 'schnoop_tbl';
        $this->databaseName = $this->getDatabaseName();

        $this->getConnection()->query(<<<SQL
DROP TABLE IF EXISTS `{$this->databaseName}`.`{$this->tableName}` 
SQL
        );

        $this->getConnection()->query(<<<SQL
CREATE TABLE `{$this->databaseName}`.`{$this->tableName}` (
  id INTEGER
) ENGINE 'InnoDB' ROW_FORMAT COMPACT COLLATE utf8mb4_general_ci COMMENT 'Table comment.'
SQL
        );

        $this->tableMapper = new TableMapper($this->getConnection());
    }

    public function testFetchRaw()
    {
        $expectedRaw = [
            'Name' => $this->tableName,
            'Engine' => 'InnoDB',
            'Row_format' => 'Compact',
            'Collation' => 'utf8mb4_general_ci',
            'Comment' => 'Table comment.'
        ];

        $this->assertSame(
            $expectedRaw,
            $this->tableMapper->fetchRaw($this->databaseName, $this->tableName)
        );
    }

    public function testNewTable()
    {
        $table = $this->tableMapper->newTable($this->tableName);

        $this->assertInstanceOf(Table::class, $table);
        $this->assertSame($this->tableName, $table->getName());
    }

    public function testCreateFromRaw()
    {
        $raw = [
            'Name' => $this->tableName,
            'Engine' => 'InnoDB',
            'Row_format' => 'COMPACT',
            'Collation' => 'utf8mb4_general_ci',
            'Comment' => 'Table comment.'
        ];

        $mockTable = $this->createMock(Table::class);
        $mockTable->expects($this->once())
            ->method('setEngine')
            ->with($raw['Engine']);
        $mockTable->expects($this->once())
            ->method('setRowFormat')
            ->with($raw['Row_format']);
        $mockTable->expects($this->once())
            ->method('setDefaultCollation')
            ->with($raw['Collation']);
        $mockTable->expects($this->once())
            ->method('setComment')
            ->with($raw['Comment']);

        /** @var TableMapper|PHPUnit_Framework_MockObject_MockObject $mockTableMapper */
        $mockTableMapper = $this->getMockBuilder(TableMapper::class)
            ->setMethods(['newTable'])
            ->setConstructorArgs([$this->createMock(MockPdo::class)])
            ->getMock();
        $mockTableMapper->method('newTable')
            ->with($this->tableName)
            ->willReturn($mockTable);

        $this->assertSame($mockTable, $mockTableMapper->createFromRaw($raw));
    }

    public function testFetch()
    {
        $raw = [];
        $mockTable = $this->createMock(Table::class);

        /** @var TableMapper|PHPUnit_Framework_MockObject_MockObject $mockTableMapper */
        $mockTableMapper = $this->getMockBuilder(TableMapper::class)
            ->setMethods(['fetchRaw', 'createFromRaw'])
            ->setConstructorArgs([$this->createMock(MockPdo::class)])
            ->getMock();
        $mockTableMapper->expects($this->once())
            ->method('fetchRaw')
            ->with($this->databaseName, $this->tableName)
            ->willReturn($raw);
        $mockTableMapper->expects($this->once())
            ->method('createFromRaw')
            ->with($raw)
            ->willReturn($mockTable);

        $this->assertSame($mockTable, $mockTableMapper->fetch($this->databaseName, $this->tableName));
    }
}
