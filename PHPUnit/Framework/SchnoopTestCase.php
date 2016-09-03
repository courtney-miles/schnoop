<?php

namespace MilesAsylum\Schnoop\PHPUnit\Framework;

use MilesAsylum\SchnoopSchema\MySQL\DataType\BinaryTypeInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\FloatType;
use MilesAsylum\SchnoopSchema\MySQL\DataType\IntTypeInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\OptionsTypeInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\StringTypeInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\TimeTypeInterface;
use PHPUnit\Framework\TestCase;

class SchnoopTestCase extends TestCase
{
    /**
     * @param string $expectedInstanceOf
     * @param int $expectedDisplayWidth
     * @param bool $expectedIsSigned
     * @param IntTypeInterface $actualIntType
     */
    public function intTypeFactoryAsserts(
        $expectedInstanceOf,
        $expectedDisplayWidth,
        $expectedIsSigned,
        $actualIntType
    ) {
        $this->assertInstanceOf($expectedInstanceOf, $actualIntType);
        $this->assertSame($expectedDisplayWidth, $actualIntType->getDisplayWidth());
        $this->assertSame($expectedIsSigned, $actualIntType->isSigned());
    }

    /**
     * @param string $expectedInstanceOf
     * @param bool $expectedIsSigned
     * @param int|null $expectedPrecision
     * @param int|null $expectedScale
     * @param FloatType $actualFloatType
     */
    public function numericPointTypeFactoryAsserts(
        $expectedInstanceOf,
        $expectedIsSigned,
        $expectedPrecision,
        $expectedScale,
        $actualFloatType
    ) {
        $this->assertInstanceOf($expectedInstanceOf, $actualFloatType);
        $this->assertSame($expectedIsSigned, $actualFloatType->isSigned());
        $this->assertSame($expectedPrecision, $actualFloatType->getPrecision());
        $this->assertSame($expectedScale, $actualFloatType->getScale());
    }

    /**
     * @param string $expectedInstanceOf
     * @param string|null $expectedCollation
     * @param int|null $expectedLength
     * @param StringTypeInterface $actualStringType
     */
    public function stringTypeFactoryAsserts(
        $expectedInstanceOf,
        $expectedCollation,
        $expectedLength,
        $actualStringType
    ) {
        $this->assertInstanceOf($expectedInstanceOf, $actualStringType);

        if (isset($expectedCollation)) {
            $this->assertSame($expectedCollation, $actualStringType->getCollation());
        }

        if (isset($expectedLength)) {
            $this->assertSame($expectedLength, $actualStringType->getLength());
        }
    }

    /**
     * @param string $expectedInstanceOf
     * @param string $expectedCollation
     * @param array $expectedOptions
     * @param OptionsTypeInterface $actualOptionType
     */
    public function optionsTypeFactoryAsserts(
        $expectedInstanceOf,
        $expectedCollation,
        array $expectedOptions,
        $actualOptionType
    ) {
        $this->assertInstanceOf($expectedInstanceOf, $actualOptionType);
        $this->assertSame($expectedCollation, $actualOptionType->getCollation());
        $this->assertSame($expectedOptions, $actualOptionType->getOptions());
    }

    /**
     * @param string $expectedInstanceOf
     * @param BinaryTypeInterface $actualBinaryType
     */
    public function binaryTypeFactoryAsserts(
        $expectedInstanceOf,
        $actualBinaryType
    ) {
        $this->assertInstanceOf($expectedInstanceOf, $actualBinaryType);
    }

    /**
     * @param string $expectedInstanceOf
     * @param int $expectedPrecision
     * @param TimeTypeInterface $actualTimeType
     */
    public function timeTypeFactoryAsserts(
        $expectedInstanceOf,
        $expectedPrecision,
        $actualTimeType
    ) {
        $this->assertInstanceOf($expectedInstanceOf, $actualTimeType);
        $this->assertSame($expectedPrecision, $actualTimeType->getPrecision());
    }
}
