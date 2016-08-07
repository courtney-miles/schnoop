<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\TimestampType;

class TimestampTypeTest extends SchnoopTestCase
{
    /**
     * @dataProvider timeTypeProvider
     * @param int $expectedPrecision
     * @param string $expectedDDL
     * @param TimestampType $actualTimestampType
     */
    public function testConstruct(
        $expectedPrecision,
        $expectedDDL,
        TimestampType $actualTimestampType
    ) {
        $this->timeTypeAsserts(
            DataTypeInterface::TYPE_TIMESTAMP,
            $expectedPrecision,
            true,
            $expectedDDL,
            $actualTimestampType
        );
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
                'TIMESTAMP',
                new TimestampType()
            ],
            [
                $precision,
                "TIMESTAMP($precision)",
                new TimestampType($precision)
            ]
        ];
    }
}
