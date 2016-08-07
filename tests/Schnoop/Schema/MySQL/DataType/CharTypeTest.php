<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\CharType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;

class CharTypeTest extends SchnoopTestCase
{
    /**
     * @dataProvider constructedProvider()
     * @param int $expectedLength
     * @param string|null $expectedCollation
     * @param string $expectedDDL
     * @param CharType $actualCharType
     */
    public function testConstructed(
        $expectedLength,
        $expectedCollation,
        $expectedDDL,
        CharType $actualCharType
    ) {
        $this->stringTypeAsserts(
            DataTypeInterface::TYPE_CHAR,
            $expectedLength,
            $expectedCollation,
            true,
            $expectedDDL,
            $actualCharType
        );
    }

    public function testCastToString()
    {
        $charType = new CharType(10);
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
                "CHAR($length) COLLATE '$collation'",
                new CharType($length, $collation)
            ],
            [
                $length,
                null,
                "CHAR($length)",
                new CharType($length)
            ]
        ];
    }
}
