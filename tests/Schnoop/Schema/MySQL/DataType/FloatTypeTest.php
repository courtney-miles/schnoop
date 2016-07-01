<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 26/06/16
 * Time: 6:08 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\FloatType;

class FloatTypeTest extends SchnoopTestCase
{
    /**
     * @var FloatType
     */
    protected $floatTypeSigned;

    /**
     * @var FloatType
     */
    protected $floatTypeUnsigned;

    protected $precision = 6;

    protected $scale = 2;

    public function setUp()
    {
        parent::setUp();

        $this->floatTypeSigned = new FloatType($this->precision, $this->scale, true);

        $this->floatTypeUnsigned = new FloatType($this->precision, $this->scale, false);
    }

    public function testConstructedSigned()
    {
        $this->assertIsNumericPointTypeConstruct(
            DataTypeInterface::TYPE_FLOAT,
            $this->precision,
            $this->scale,
            true,
            '-9999.99',
            '9999.99',
            true,
            $this->floatTypeSigned
        );
    }

    public function testConstructedUnsigned()
    {
        $this->assertIsNumericPointTypeConstruct(
            DataTypeInterface::TYPE_FLOAT,
            $this->precision,
            $this->scale,
            false,
            '0',
            '9999.99',
            true,
            $this->floatTypeUnsigned
        );
    }

    public function testCast()
    {
        $this->assertSame(123.23, $this->floatTypeSigned->cast('123.23'));
    }
}
