<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 29/06/16
 * Time: 7:23 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema;

use MilesAsylum\Schnoop\Schema\AbstractCommonColumn;
use MilesAsylum\Schnoop\Schema\CommonDataTypeInterface;
use MilesAsylum\Schnoop\Schema\CommonTableInterface;
use PHPUnit_Framework_MockObject_MockObject;

class AbstractCommonColumnTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractCommonColumn
     */
    protected $abstractCommonColumn;

    protected $name = 'schnoop_column';

    /**
     * @var CommonDataTypeInterface
     */
    protected $mockDataType;

    public function setUp()
    {
        parent::setUp();

        $this->mockDataType = $this->createMock('MilesAsylum\Schnoop\Schema\CommonDataTypeInterface');

        $this->abstractCommonColumn = $this->getMockForAbstractClass(
            'MilesAsylum\Schnoop\Schema\AbstractCommonColumn',
            [$this->name, $this->mockDataType]
        );
    }

    public function testConstruct()
    {
        $this->assertSame($this->name, $this->abstractCommonColumn->getName());
        $this->assertSame($this->mockDataType, $this->abstractCommonColumn->getDataType());
        $this->assertNull($this->abstractCommonColumn->getTable());
    }

    public function testSetTable()
    {
        $tableName = 'schnoop_table';
        /** @var CommonTableInterface|PHPUnit_Framework_MockObject_MockObject $mockTable */
        $mockTable = $this->createMock('MilesAsylum\Schnoop\Schema\CommonTableInterface');
        $mockTable->method('getName')->willReturn($tableName);

        $this->abstractCommonColumn->setTable($mockTable);
        $this->assertSame($mockTable, $this->abstractCommonColumn->getTable());

        return $this->abstractCommonColumn;
    }

    /**
     * @depends testSetTable
     * @expectedException \MilesAsylum\Schnoop\Schema\Exception\ColumnException
     * @expectedExceptionMessage Attempt made to attach column schnoop_column to table schnoop_table2 when it is already attached to schnoop_table
     * @param AbstractCommonColumn $abstractCommonColumn
     */
    public function testExceptionWhenTableAlreadySet(AbstractCommonColumn $abstractCommonColumn)
    {
        $tableName = 'schnoop_table2';
        /** @var CommonTableInterface|PHPUnit_Framework_MockObject_MockObject $mockTable */
        $mockTable = $this->createMock('MilesAsylum\Schnoop\Schema\CommonTableInterface');
        $mockTable->method('getName')->willReturn($tableName);

        $abstractCommonColumn->setTable($mockTable);
    }
}
