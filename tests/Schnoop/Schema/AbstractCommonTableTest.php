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

    public function setUp()
    {
        parent::setUp();

        $mockColumn = $this->createMock('MilesAsylum\Schnoop\Schema\CommonColumnInterface');
        $mockColumn->method('getName')->willReturn($this->columnName);

        $this->mockColumns[] = $mockColumn;

        $this->abstractCommonTable = $this->getMockForAbstractClass(
            'MilesAsylum\Schnoop\Schema\AbstractCommonTable',
            [$this->name, $this->mockColumns]
        );
    }

    public function testConstruct()
    {
        $this->assertSame($this->name, $this->abstractCommonTable->getName());
        $this->assertSame($this->mockColumns, $this->abstractCommonTable->getColumns());
    }

    public function testGetColumn()
    {
        $this->assertSame(
            reset($this->mockColumns),
            $this->abstractCommonTable->getColumn($this->columnName)
        );
    }
}
