<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 21/06/16
 * Time: 5:51 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DecimalType;

class DecimalTypeTest extends SchnoopTestCase
{
    /**
     * @var DecimalType
     */
    protected $decimalTypeSigned;

    /**
     * @var DecimalType
     */
    protected $decimalTypeUnsigned;

    protected $precision = 6;

    protected $scale = 2;

    public function setUp()
    {
        parent::setUp();

        $this->decimalTypeSigned = new DecimalType(
            $this->precision,
            $this->scale,
            true
        );

        $this->decimalTypeUnsigned = new DecimalType(
            $this->precision,
            $this->scale,
            false
        );
    }

    public function testConstructedSigned()
    {
        $this->assertIsNumericPointTypeConstruct(
            DataTypeInterface::TYPE_DECIMAL,
            $this->precision,
            $this->scale,
            true,
            '-9999.99',
            '9999.99',
            true,
            $this->decimalTypeSigned
        );
    }

    public function testConstructedUnsigned()
    {
        $this->assertIsNumericPointTypeConstruct(
            DataTypeInterface::TYPE_DECIMAL,
            $this->precision,
            $this->scale,
            false,
            '0',
            '9999.99',
            true,
            $this->decimalTypeUnsigned
        );
    }

    public function testCast()
    {
        $this->assertSame('123.45', $this->decimalTypeSigned->cast(123.45));
    }
}
