<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\Table;

use InvalidArgumentException;
use MilesAsylum\Schnoop\PHPUnit\Framework\TestMySQLCase;
use MilesAsylum\Schnoop\PHPUnit\Schnoop\MockPdo;
use MilesAsylum\Schnoop\SchemaAdapter\MySQL\Table;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Table\TableFactory;
use PHPUnit\Framework\MockObject\MockObject;

class TableFactoryTest extends TestMySQLCase
{
    /**
     * @var TableFactory
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

        $this->tableMapper = new TableFactory($this->getConnection());
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
            $this->tableMapper->fetchRaw($this->tableName, $this->databaseName)
        );
    }

    public function testFetchRawForTableNotFound(): void
    {
        $this->assertNull(
            $this->tableMapper->fetchRaw('_bogus_', $this->databaseName)
        );
    }

    public function testNewTable()
    {
        $table = $this->tableMapper->newTable($this->tableName, $this->databaseName);

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

        /** @var TableFactory|MockObject $mockTableMapper */
        $mockTableMapper = $this->getMockBuilder(TableFactory::class)
            ->setMethods(['newTable'])
            ->setConstructorArgs([$this->createMock(MockPdo::class)])
            ->getMock();
        $mockTableMapper->method('newTable')
            ->with($this->tableName, $this->databaseName)
            ->willReturn($mockTable);

        $this->assertSame($mockTable, $mockTableMapper->createFromRaw($raw, $this->databaseName));
    }

    public function testFetch()
    {
        $raw = [];
        $mockTable = $this->createMock(Table::class);

        /** @var TableFactory|MockObject $mockTableFactory */
        $mockTableFactory = $this->getMockBuilder(TableFactory::class)
            ->setMethods(['fetchRaw', 'createFromRaw'])
            ->setConstructorArgs([$this->createMock(MockPdo::class)])
            ->getMock();
        $mockTableFactory->expects($this->once())
            ->method('fetchRaw')
            ->with($this->tableName, $this->databaseName)
            ->willReturn($raw);
        $mockTableFactory->expects($this->once())
            ->method('createFromRaw')
            ->with($raw)
            ->willReturn($mockTable);

        $this->assertSame($mockTable, $mockTableFactory->fetch($this->tableName, $this->databaseName));
    }

    public function testExceptionOnFetchTableNotFound(): void
    {
        $bogusTableName = '_bogus_';

        /** @var TableFactory|MockObject $mockTableFactory */
        $mockTableFactory = $this->getMockBuilder(TableFactory::class)
            ->setMethods(['fetchRaw'])
            ->setConstructorArgs([$this->createMock(MockPdo::class)])
            ->getMock();
        $mockTableFactory->expects($this->once())
            ->method('fetchRaw')
            ->with($bogusTableName, $this->databaseName)
            ->willReturn(null);

        self::assertNull($mockTableFactory->fetch($bogusTableName, $this->databaseName));
    }
}
