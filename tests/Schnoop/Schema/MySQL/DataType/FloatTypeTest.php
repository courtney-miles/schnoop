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
     * @dataProvider constructedProvider
     * @param bool $expectedIsSigned
     * @param int|null $expectedPrecision
     * @param int|null $expectedScale
     * @param string|null $expectedMinRange
     * @param string|null $expectedMaxRange
     * @param FloatType $actualFloatType
     */
    public function testConstructed(
        $expectedIsSigned,
        $expectedPrecision,
        $expectedScale,
        $expectedMinRange,
        $expectedMaxRange,
        $actualFloatType
    ) {
        $this->numericPointTypeAsserts(
            DataTypeInterface::TYPE_FLOAT,
            $expectedIsSigned,
            $expectedPrecision,
            $expectedScale,
            $expectedMinRange,
            $expectedMaxRange,
            true,
            $actualFloatType
        );
    }

    public function testCast()
    {
        $floatType = new FloatType(true);

        $this->assertSame(123.23, $floatType->cast('123.23'));
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
                new FloatType(true, 6, 2)
            ],
            [
                false,
                6,
                2,
                '0',
                '9999.99',
                new FloatType(false, 6, 2)
            ],
            [
                true,
                null,
                null,
                null,
                null,
                new FloatType(true)
            ]
        ];
    }
}
