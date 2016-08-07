<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\LongTextType;

class LongTextTypeTest extends SchnoopTestCase
{
    /**
     * @dataProvider constructedProvider()
     * @param int $expectedLength
     * @param string|null $expectedCollation
     * @param string $expectedDDL
     * @param LongTextType $actualLongTextType
     */
    public function testConstructed(
        $expectedLength,
        $expectedCollation,
        $expectedDDL,
        LongTextType $actualLongTextType
    ) {
        $this->stringTypeAsserts(
            DataTypeInterface::TYPE_LONGTEXT,
            $expectedLength,
            $expectedCollation,
            false,
            $expectedDDL,
            $actualLongTextType
        );
    }

    public function testCast()
    {
        $longTextType = new LongTextType();
        $this->assertSame('123', $longTextType->cast(123));
    }

    /**
     * @see testConstructed()
     * @return array
     */
    public function constructedProvider()
    {
        $length = pow(2, 32) -1;
        $collation = 'utf8_general_ci';

        return [
            [
                $length,
                $collation,
                "LONGTEXT COLLATE '$collation'",
                new LongTextType($collation)
            ],
            [
                $length,
                null,
                'LONGTEXT',
                new LongTextType()
            ]
        ];
    }
}
