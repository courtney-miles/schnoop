<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 29/06/16
 * Time: 7:15 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema;

use MilesAsylum\Schnoop\Schema\AbstractCommonTable;
use MilesAsylum\Schnoop\Schema\CommonColumnInterface;
use MilesAsylum\Schnoop\Schema\CommonIndexInterface;

class AbstractCommonTableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractCommonTable
     */
    protected $abstractCommonTable;

    protected $name = 'schnoop_table';

    /**
     * @var CommonColumnInterface[]
     */
    protected $mockColumns = [];

    protected $columnName = 'schnoop_column';

    /**
     * @var CommonColumnInterface[]
     */
    protected $mockIndexes = [];

    protected $indexName = 'schnoop_idx';

    public function setUp()
    {
        parent::setUp();

        $mockColumn = $this->createMock(CommonColumnInterface::class);
        $mockColumn->method('getName')->willReturn($this->columnName);
        $this->mockColumns[] = $mockColumn;

        $mockIndex = $this->createMock(CommonIndexInterface::class);
        $mockIndex->method('getName')->willReturn($this->indexName);
        $this->mockIndexes[] = $mockIndex;

        $this->abstractCommonTable = $this->getMockForAbstractClass(
            AbstractCommonTable::class,
            [
                $this->name,
                $this->mockColumns,
                $this->mockIndexes
            ]
        );
    }

    public function testConstruct()
    {
        $this->assertSame($this->name, $this->abstractCommonTable->getName());
        $this->assertSame($this->mockColumns, $this->abstractCommonTable->getColumns());
        $this->assertSame($this->mockIndexes, $this->abstractCommonTable->getIndexes());
    }

    public function testGetColumnList()
    {
        $this->assertSame([$this->columnName], $this->abstractCommonTable->getColumnList());
    }

    public function testGetColumns()
    {
        $this->assertSame($this->mockColumns, $this->abstractCommonTable->getColumns());
    }

    public function testHasColumn()
    {
        $this->assertTrue($this->abstractCommonTable->hasColumn($this->columnName));
    }

    public function testHasColumnNotFound()
    {
        $this->assertFalse($this->abstractCommonTable->hasColumn('bogus'));
    }

    public function testGetColumn()
    {
        $this->assertSame(
            reset($this->mockColumns),
            $this->abstractCommonTable->getColumn($this->columnName)
        );
    }

    public function testGetColumnNotFound()
    {
        $this->assertNull(
            $this->abstractCommonTable->getColumn('bogus')
        );
    }

    public function testGetIndexList()
    {
        $this->assertSame([$this->indexName], $this->abstractCommonTable->getIndexList());
    }

    public function testGetIndexes()
    {
        $this->assertSame($this->mockIndexes, $this->abstractCommonTable->getIndexes());
    }

    public function testHasIndex()
    {
        $this->assertTrue($this->abstractCommonTable->hasIndex($this->indexName));
    }

    public function testHasIndexNotFound()
    {
        $this->assertFalse($this->abstractCommonTable->hasIndex('bogus'));
    }

    public function testGetIndex()
    {
        $this->assertSame(
            reset($this->mockIndexes),
            $this->abstractCommonTable->getIndex($this->indexName)
        );
    }

    public function testGetIndexNotFound()
    {
        $this->assertNull($this->abstractCommonTable->getIndex('bougs'));
    }
}
