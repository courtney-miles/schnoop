<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 26/06/16
 * Time: 6:04 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DoubleType;

class DoubleTypeTest extends SchnoopTestCase
{
    /**
     * @var DoubleType
     */
    protected $doubleTypeSigned;

    /**
     * @var DoubleType
     */
    protected $doubleTypeUnsigned;

    protected $precision = 6;

    protected $scale = 2;

    public function setUp()
    {
        parent::setUp();

        $this->doubleTypeSigned = new DoubleType($this->precision, $this->scale, true);

        $this->doubleTypeUnsigned = new DoubleType($this->precision, $this->scale, false);
    }

    public function testConstructedSigned()
    {
        $this->assertIsNumericPointTypeConstruct(
            DataTypeInterface::TYPE_DOUBLE,
            $this->precision,
            $this->scale,
            true,
            '-9999.99',
            '9999.99',
            true,
            $this->doubleTypeSigned
        );
    }

    public function testConstructedUnsigned()
    {
        $this->assertIsNumericPointTypeConstruct(
            DataTypeInterface::TYPE_DOUBLE,
            $this->precision,
            $this->scale,
            false,
            '0',
            '9999.99',
            true,
            $this->doubleTypeUnsigned
        );
    }

    public function testCast()
    {
        $this->assertSame(123.23, $this->doubleTypeSigned->cast('123.23'));
    }
}
