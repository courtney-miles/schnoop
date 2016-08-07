<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\MediumIntType;

class MediumIntTypeTest extends SchnoopTestCase
{
    /**
     * @dataProvider constructedProvider()
     * @param int $expectedDisplayWidth
     * @param bool $expectedIsSigned
     * @param int $expectedMinRange
     * @param int $expectedMaxRange
     * @param string $expectedDDL
     * @param MediumIntType $actualMediumIntType
     */
    public function testConstructed(
        $expectedDisplayWidth,
        $expectedIsSigned,
        $expectedMinRange,
        $expectedMaxRange,
        $expectedDDL,
        MediumIntType $actualMediumIntType
    ) {
        $this->intTypeAsserts(
            DataTypeInterface::TYPE_MEDIUMINT,
            $expectedDisplayWidth,
            $expectedIsSigned,
            $expectedMinRange,
            $expectedMaxRange,
            true,
            $expectedDDL,
            $actualMediumIntType
        );
    }

    public function testCast()
    {
        $mediumIntType = new MediumIntType(10, true);
        $this->assertSame(123, $mediumIntType->cast('123'));
    }

    /**
     * @see testConstructed()
     * @return array
     */
    public function constructedProvider()
    {
        $displayWidth = 10;
        $signed = true;
        $notSigned = false;

        return [
            [
                $displayWidth,
                $signed,
                -pow(2, 24)/2,
                pow(2, 24)/2-1,
                "MEDIUMINT($displayWidth)",
                new MediumIntType($displayWidth, $signed)
            ],
            [
                $displayWidth,
                $notSigned,
                0,
                pow(2, 24)-1,
                "MEDIUMINT($displayWidth) UNSIGNED",
                new MediumIntType($displayWidth, $notSigned)
            ]
        ];
    }
}
