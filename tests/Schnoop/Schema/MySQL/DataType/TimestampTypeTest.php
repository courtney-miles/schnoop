<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 12/07/16
 * Time: 7:35 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\TimestampType;

class TimestampTypeTest extends SchnoopTestCase
{
    /**
     * @dataProvider timeTypeProvider
     * @param $expectedName
     * @param $expectedPrecision
     * @param $expectedAllowDefault
     * @param $valueToCast
     * @param $expectedCastValue
     * @param TimestampType $actualTimeType
     */
    public function testConstruct(
        $expectedName,
        $expectedPrecision,
        $expectedAllowDefault,
        $valueToCast,
        $expectedCastValue,
        TimestampType $actualTimeType
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
        $timeTypeDefaultPrecision = new TimestampType();

        $precision = 3;
        $timeTypeExplicitPrecision = new TimestampType($precision);

        return [
            [
                DataTypeInterface::TYPE_TIMESTAMP,
                0,
                true,
                '2016-01-01 11:59:59',
                '2016-01-01 11:59:59',
                $timeTypeDefaultPrecision
            ],
            [
                DataTypeInterface::TYPE_TIMESTAMP,
                $precision,
                true,
                '2016-01-01 11:59:59.000',
                '2016-01-01 11:59:59.000',
                $timeTypeExplicitPrecision
            ]
        ];
    }
}
