<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/06/16
 * Time: 7:33 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\Column;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\Column\NumericColumn;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\NumericTypeInterface;
use PHPUnit_Framework_MockObject_MockObject;

class NumericColumnTest extends SchnoopTestCase
{
    /**
     * @var NumericColumn
     */
    protected $numericColumn;

    protected $name = 'schnoop_deccolumn';

    /**
     * @var NumericTypeInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockNumericType;

    protected $zeroFill = true;

    protected $allowNull = true;

    /**
     * Use a float to test that the value will be cast.
     * @var float
     */
    protected $default = '123.45';

    protected $autoIncrement = true;

    protected $comment = 'Schnoop comment';

    public function setUp()
    {
        parent::setUp();

        $this->mockNumericType = $this->createMock(
            'MilesAsylum\Schnoop\Schema\MySQL\DataType\NumericTypeInterface'
        );
        $this->mockNumericType->method('cast')
            ->willReturn((float)$this->default);
        $this->mockNumericType->method('allowDefault')
            ->willReturn(true);

        $this->numericColumn = new NumericColumn(
            $this->name,
            $this->mockNumericType,
            $this->zeroFill,
            $this->allowNull,
            $this->default,
            $this->autoIncrement,
            $this->comment
        );
    }

    public function testConstructed()
    {
        $this->assertIsColumnConstruct(
            $this->name,
            $this->mockNumericType,
            $this->allowNull,
            true,
            (float)$this->default,
            $this->comment,
            $this->numericColumn
        );
        
        $this->assertSame($this->zeroFill, $this->numericColumn->isZeroFill());
        $this->assertSame($this->autoIncrement, $this->numericColumn->isAutoIncrement());
        $this->assertSame($this->comment, $this->numericColumn->getComment());
    }
}
