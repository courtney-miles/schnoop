<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DateTimeType;

class DateTimeTypeTest extends SchnoopTestCase
{
    /**
     * @dataProvider timeTypeProvider
     * @param int $expectedPrecision
     * @param string $expectedDDL
     * @param DateTimeType $actualTimeType
     */
    public function testConstruct(
        $expectedPrecision,
        $expectedDDL,
        DateTimeType $actualTimeType
    ) {
        $this->timeTypeAsserts(
            DataTypeInterface::TYPE_DATETIME,
            $expectedPrecision,
            true,
            $expectedDDL,
            $actualTimeType
        );
    }

    public function testCast()
    {
        $dateTime = '2016-01-01 11:59:59';
        $dateTimeType = new DateTimeType();

        $this->assertSame($dateTime, $dateTimeType->cast($dateTime));
    }

    /**
     * @see testConstruct
     */
    public function timeTypeProvider()
    {
        $precision = 3;

        return [
            [
                0,
                'DATETIME',
                new DateTimeType()
            ],
            [
                $precision,
                "DATETIME($precision)",
                new DateTimeType($precision)
            ]
        ];
    }
}
