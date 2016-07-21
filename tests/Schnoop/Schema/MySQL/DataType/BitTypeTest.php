<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 28/06/16
 * Time: 9:46 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\BitType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;

class BitTypeTest extends SchnoopTestCase
{
    /**
     * @var BitType
     */
    protected $bitType;

    protected $length = '3';

    public function setUp()
    {
        parent::setUp();

        $this->bitType = new BitType($this->length);
    }

    public function testConstruct()
    {
        $this->assertSame(DataTypeInterface::TYPE_BIT, $this->bitType->getName());
        $this->assertSame((int)$this->length, $this->bitType->getLength());
        $this->assertSame(0, $this->bitType->getMinRange());
        $this->assertSame(pow(2, 3), $this->bitType->getMaxRange());
        $this->assertTrue($this->bitType->doesAllowDefault());
    }

    public function testCast()
    {
        $this->assertSame(123, $this->bitType->cast('123'));
    }
}
