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
     * @dataProvider constructedProvider
     * @param bool $expectedIsSigned
     * @param int|null $expectedPrecision
     * @param int|null $expectedScale
     * @param string|null $expectedMinRange
     * @param string|null $expectedMaxRange
     * @param DecimalType $actualDecimalType
     */
    public function testConstructed(
        $expectedIsSigned,
        $expectedPrecision,
        $expectedScale,
        $expectedMinRange,
        $expectedMaxRange,
        $actualDecimalType
    ) {
        $this->numericPointTypeAsserts(
            DataTypeInterface::TYPE_DECIMAL,
            $expectedIsSigned,
            $expectedPrecision,
            $expectedScale,
            $expectedMinRange,
            $expectedMaxRange,
            true,
            $actualDecimalType
        );
    }

    public function testCast()
    {
        $decimalType = new DecimalType(true, 10, 0);

        $this->assertSame('123.45', $decimalType->cast(123.45));
    }

    /**
     * @see testConstructed
     * @return array
     */
    public function constructedProvider()
    {
        return [
            [
                true,
                6,
                2,
                '-9999.99',
                '9999.99',
                new DecimalType(true, 6, 2)
            ],
            [
                false,
                6,
                2,
                '0',
                '9999.99',
                new DecimalType(false, 6, 2)
            ]
        ];
    }
}
