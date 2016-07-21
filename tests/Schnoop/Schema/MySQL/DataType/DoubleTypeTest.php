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
     * @dataProvider constructedProvider
     * @param bool $expectedIsSigned
     * @param int|null $expectedPrecision
     * @param int|null $expectedScale
     * @param string|null $expectedMinRange
     * @param string|null $expectedMaxRange
     * @param DoubleType $actualDecimalType
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
            DataTypeInterface::TYPE_DOUBLE,
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
        $doubleType = new DoubleType(true);

        $this->assertSame(123.23, $doubleType->cast('123.23'));
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
                new DoubleType(true, 6, 2)
            ],
            [
                false,
                6,
                2,
                '0',
                '9999.99',
                new DoubleType(false, 6, 2)
            ],
            [
                true,
                null,
                null,
                null,
                null,
                new DoubleType(true)
            ]
        ];
    }
}
