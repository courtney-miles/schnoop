<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 12/07/16
 * Time: 7:26 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DateTimeType;

class DateTimeTypeTest extends SchnoopTestCase
{
    /**
     * @dataProvider timeTypeProvider
     * @param $expectedName
     * @param $expectedPrecision
     * @param $expectedAllowDefault
     * @param $valueToCast
     * @param $expectedCastValue
     * @param DateTimeType $actualTimeType
     */
    public function testConstruct(
        $expectedName,
        $expectedPrecision,
        $expectedAllowDefault,
        $valueToCast,
        $expectedCastValue,
        DateTimeType $actualTimeType
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
        $timeTypeDefaultPrecision = new DateTimeType();

        $precision = 3;
        $timeTypeExplicitPrecision = new DateTimeType($precision);

        return [
            [
                DataTypeInterface::TYPE_DATETIME,
                0,
                true,
                '2016-01-01 11:59:59',
                '2016-01-01 11:59:59',
                $timeTypeDefaultPrecision
            ],
            [
                DataTypeInterface::TYPE_DATETIME,
                $precision,
                true,
                '2016-01-01 11:59:59.000',
                '2016-01-01 11:59:59.000',
                $timeTypeExplicitPrecision
            ]
        ];
    }
}
