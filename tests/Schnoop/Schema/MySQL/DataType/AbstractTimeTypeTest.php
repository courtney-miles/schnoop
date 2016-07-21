<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 11/07/16
 * Time: 4:41 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\AbstractTimeType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\TimeTypeInterface;

class AbstractTimeTypeTest extends SchnoopTestCase
{
    /**
     * @dataProvider abstractTimeTypeProvider
     * @param int $expectedPrecision
     * @param bool $expectedAllowDefault
     * @param string $valueToCast
     * @param string $expectedCastValue
     * @param AbstractTimeType $actualTimeType
     */
    public function testConstruct(
        $expectedPrecision,
        $expectedAllowDefault,
        $valueToCast,
        $expectedCastValue,
        AbstractTimeType $actualTimeType
    ) {
        $this->assertSame($expectedPrecision, $actualTimeType->getPrecision());
        $this->assertSame($expectedAllowDefault, $actualTimeType->doesAllowDefault());
        $this->assertSame($expectedCastValue, $actualTimeType->cast($valueToCast));
    }

    /**
     * @see testConstruct
     */
    public function abstractTimeTypeProvider()
    {
        $timeTypeDefaultPrecision = $this->getMockForAbstractClass(
            '\MilesAsylum\Schnoop\Schema\MySQL\DataType\AbstractTimeType'
        );

        $precision = 3;
        $timeTypeExplicitPrecision = $this->getMockForAbstractClass(
            '\MilesAsylum\Schnoop\Schema\MySQL\DataType\AbstractTimeType',
            [$precision]
        );

        return [
            [
                0,
                true,
                '11:59:59',
                '11:59:59',
                $timeTypeDefaultPrecision
            ],
            [
                $precision,
                true,
                '11:59:59.000',
                '11:59:59.000',
                $timeTypeExplicitPrecision
            ]
        ];
    }
}
