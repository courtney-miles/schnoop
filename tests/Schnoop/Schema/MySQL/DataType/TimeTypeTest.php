<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 12/07/16
 * Time: 7:22 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\TimeType;

class TimeTypeTest extends SchnoopTestCase
{
    /**
     * @dataProvider timeTypeProvider
     * @param $expectedName
     * @param $expectedPrecision
     * @param $expectedAllowDefault
     * @param $valueToCast
     * @param $expectedCastValue
     * @param TimeType $actualTimeType
     */
    public function testConstruct(
        $expectedName,
        $expectedPrecision,
        $expectedAllowDefault,
        $valueToCast,
        $expectedCastValue,
        TimeType $actualTimeType
    ) {
        $this->timeTypeAsserts(
            $expectedName,
            $expectedPrecision,
            $expectedAllowDefault,
            $valueToCast,
            $expectedCastValue,
            $actualTimeType
        );
    }

    /**
     * @see testConstruct
     */
    public function timeTypeProvider()
    {
        $timeTypeDefaultPrecision = new TimeType();

        $precision = 3;
        $timeTypeExplicitPrecision = new TimeType($precision);

        return [
            [
                DataTypeInterface::TYPE_TIME,
                0,
                true,
                '11:59:59',
                '11:59:59',
                $timeTypeDefaultPrecision
            ],
            [
                DataTypeInterface::TYPE_TIME,
                $precision,
                true,
                '11:59:59.000',
                '11:59:59.000',
                $timeTypeExplicitPrecision
            ]
        ];
    }
}
