<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 27/06/16
 * Time: 7:43 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\Table;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\Column\ColumnInterface;
use MilesAsylum\Schnoop\Schema\MySQL\Index\IndexInterface;
use MilesAsylum\Schnoop\Schema\MySQL\Table\Table;

class TableTest extends SchnoopTestCase
{
    /**
     * @var Table
     */
    protected $table;

    protected $name;

    /**
     * @var ColumnInterface[]
     */
    protected $mockColumns = [];

    protected $columnName = 'schnoop_col';

    /**
     * @var IndexInterface[]
     */
    protected $mockIndexes = [];

    protected $indexName = 'schnoop_idx';

    protected $engine;

    protected $rowFormat;

    protected $defaultCollation;

    protected $comment;

    public function setUp()
    {
        parent::setUp();

        $mockColumn = $this->createMock(ColumnInterface::class);
        $mockColumn->method('getName')
            ->willReturn($this->columnName);
        $mockColumn->expects($this->once())
            ->method('setTable')
            ->with($this->isInstanceOf(Table::class));
        $this->mockColumns[] = $mockColumn;

        $mockIndex = $this->createMock(IndexInterface::class);
        $mockIndex->method('getName')
            ->willReturn($this->indexName);
        $this->mockIndexes[] = $mockIndex;

        $this->table = new Table(
            $this->name,
            $this->mockColumns,
            $this->mockIndexes,
            $this->engine,
            $this->rowFormat,
            $this->defaultCollation,
            $this->comment
        );
    }

    public function testConstruct()
    {
        $this->assertSame($this->name, $this->table->getName());
        $this->assertSame($this->mockColumns, $this->table->getColumns());
        $this->assertSame($this->engine, $this->table->getEngine());
        $this->assertSame($this->rowFormat, $this->table->getRowFormat());
        $this->assertSame($this->defaultCollation, $this->table->getDefaultCollation());
        $this->assertSame($this->comment, $this->table->getComment());
    }

    public function testGetColumn()
    {
        $this->assertSame(reset($this->mockColumns), $this->table->getColumn($this->columnName));
    }

    public function testGetIndex()
    {
        $this->assertSame(reset($this->mockIndexes), $this->table->getIndex($this->indexName));
    }
}
