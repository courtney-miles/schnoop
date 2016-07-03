<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/06/16
 * Time: 6:51 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\Column;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\Column\Column;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use PHPUnit_Framework_MockObject_MockObject;

class ColumnTest extends SchnoopTestCase
{
    /**
     * @var Column
     */
    protected $column;

    protected $name = 'schnoop_col';

    /**
     * @var DataTypeInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockDataType;

    protected $allowNull = true;
    
    protected $default = '123';

    protected $comment = 'Schnoop column.';

    public function setUp()
    {
        parent::setUp();

        $this->mockDataType = $this->createMock('MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface');
        $this->mockDataType->method('cast')
            ->willReturn((int)$this->default);
        $this->mockDataType->method('allowDefault')
            ->willReturn(true);

        $this->column = new Column(
            $this->name,
            $this->mockDataType,
            $this->allowNull,
            $this->default,
            $this->comment
        );
    }

    public function testConstructed()
    {
        $this->assertIsColumnConstruct(
            $this->name,
            $this->mockDataType,
            $this->allowNull,
            true,
            (int)$this->default,
            $this->comment,
            $this->column
        );
    }

    public function testHasDefaultWhenAllowNull()
    {
        /** @var DataTypeInterface|PHPUnit_Framework_MockObject_MockObject $mockDataType */
        $mockDataType = $this->createMock('MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface');
        $mockDataType->method('cast')
            ->willReturn(null);
        $mockDataType->method('allowDefault')
            ->willReturn(true);

        /** @var Column $column */
        $column = new Column(
            $this->name,
            $mockDataType,
            true,
            null,
            $this->comment
        );

        $this->assertIsColumnConstruct(
            $this->name,
            $mockDataType,
            true,
            true,
            null,
            $this->comment,
            $column
        );
    }

    public function testNotHasDefaultWhenNotAllowNull()
    {
        /** @var DataTypeInterface|PHPUnit_Framework_MockObject_MockObject $mockDataType */
        $mockDataType = $this->createMock('MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface');
        $mockDataType->method('cast')
            ->willReturn('');
        $mockDataType->method('allowDefault')
            ->willReturn(true);

        $column = new Column(
            $this->name,
            $mockDataType,
            false,
            null,
            $this->comment
        );

        $this->assertIsColumnConstruct(
            $this->name,
            $mockDataType,
            false,
            false,
            null,
            $this->comment,
            $column
        );
    }

    /**
     * @expectedException \PHPUnit_Framework_Error_Warning
     * @expectedExceptionMessage Attempt made to set a default for a data-type that does not support. The supplied default value has been ignored.
     */
    public function testWarningWhenSetDefaultWhenNotAllowed()
    {
        /** @var DataTypeInterface|PHPUnit_Framework_MockObject_MockObject $mockDataType */
        $mockDataType = $this->createMock('MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface');
        $mockDataType->method('cast')
            ->willReturn('');
        $mockDataType->method('allowDefault')
            ->willReturn(false);

        $column = new Column(
            $this->name,
            $mockDataType,
            false,
            'Foo',
            $this->comment
        );
    }
}
