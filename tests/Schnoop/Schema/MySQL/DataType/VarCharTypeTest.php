<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\VarCharType;

class VarCharTypeTest extends SchnoopTestCase
{
    /**
     * @dataProvider constructedProvider()
     * @param int $expectedLength
     * @param string|null $expectedCollation
     * @param string $expectedDDL
     * @param VarCharType $actualCharType
     */
    public function testConstructed(
        $expectedLength,
        $expectedCollation,
        $expectedDDL,
        VarCharType $actualCharType
    ) {
        $this->stringTypeAsserts(
            DataTypeInterface::TYPE_VARCHAR,
            $expectedLength,
            $expectedCollation,
            true,
            $expectedDDL,
            $actualCharType
        );
    }

    public function testCastToString()
    {
        $charType = new VarCharType(10);
        $this->assertSame('123', $charType->cast(123));
    }

    /**
     * @see testConstructed()
     * @return array
     */
    public function constructedProvider()
    {
        $length = 10;
        $collation = 'utf8_general_ci';

        return [
            [
                $length,
                $collation,
                "VARCHAR($length) COLLATE '$collation'",
                new VarCharType($length, $collation)
            ],
            [
                $length,
                null,
                "VARCHAR($length)",
                new VarCharType($length)
            ]
        ];
    }
}
