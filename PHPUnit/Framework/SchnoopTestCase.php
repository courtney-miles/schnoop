<?php

namespace MilesAsylum\Schnoop\PHPUnit\Framework;

use MilesAsylum\Schnoop\Schema\MySQL\Column\ColumnInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\BinaryTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\FloatType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\IntTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\NumericPointTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\OptionsTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\StringTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\TimeTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\Index\IndexInterface;
use PHPUnit\Framework\TestCase;

class SchnoopTestCase extends TestCase
{
    /**
     * @param string $expectedName
     * @param int $expectedDisplayWidth
     * @param bool $expectedSigned
     * @param int $expectedMinRange
     * @param int $expectedMaxRange
     * @param bool $expectedAllowDefault
     * @param IntTypeInterface $actualIntType
     */
    public function intTypeAsserts(
        $expectedName,
        $expectedDisplayWidth,
        $expectedSigned,
        $expectedMinRange,
        $expectedMaxRange,
        $expectedAllowDefault,
        IntTypeInterface $actualIntType
    ) {
        $this->assertSame($expectedName, $actualIntType->getName());
        $this->assertSame($expectedDisplayWidth, $actualIntType->getDisplayWidth());
        $this->assertSame($expectedSigned, $actualIntType->isSigned());
        $this->assertSame($expectedMinRange, $actualIntType->getMinRange());
        $this->assertSame($expectedMaxRange, $actualIntType->getMaxRange());
        $this->assertSame($expectedAllowDefault, $actualIntType->doesAllowDefault());
    }

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
     * @param string $expectedName
     * @param bool $expectedSigned
     * @param int|null $expectedPrecision
     * @param int|null $expectedScale
     * @param string|null $expectedMinRange
     * @param string|null $expectedMaxRange
     * @param bool $expectedAllowDefault
     * @param NumericPointTypeInterface $actualNumericPointType
     */
    public function numericPointTypeAsserts(
        $expectedName,
        $expectedSigned,
        $expectedPrecision,
        $expectedScale,
        $expectedMinRange,
        $expectedMaxRange,
        $expectedAllowDefault,
        NumericPointTypeInterface $actualNumericPointType
    ) {
        $this->assertSame($expectedName, $actualNumericPointType->getName());
        $this->assertSame($expectedPrecision, $actualNumericPointType->getPrecision());
        $this->assertSame($expectedScale, $actualNumericPointType->getScale());
        $this->assertSame($expectedSigned, $actualNumericPointType->isSigned());
        $this->assertSame($expectedMinRange, $actualNumericPointType->getMinRange());
        $this->assertSame($expectedMaxRange, $actualNumericPointType->getMaxRange());
        $this->assertSame($expectedAllowDefault, $actualNumericPointType->doesAllowDefault());
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
     * @param string $expectedName
     * @param int $expectedLength
     * @param string $expectedCollation
     * @param bool $allowDefault
     * @param StringTypeInterface $actualStringType
     * @internal param string $expectedCharacterSet
     */
    public function stringTypeAsserts(
        $expectedName,
        $expectedLength,
        $expectedCollation,
        $allowDefault,
        StringTypeInterface $actualStringType
    ) {
        $this->assertSame($expectedName, $actualStringType->getName());
        $this->assertSame($expectedLength, $actualStringType->getLength());
        $this->assertSame($expectedCollation, $actualStringType->getCollation());
        $this->assertSame($allowDefault, $actualStringType->doesAllowDefault());
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
     * @param string $expectedName
     * @param int $expectedLength
     * @param $expectedAllowDefault
     * @param BinaryTypeInterface $actualStringType
     */
    public function binaryTypeAsserts(
        $expectedName,
        $expectedLength,
        $expectedAllowDefault,
        BinaryTypeInterface $actualStringType
    ) {
        $this->assertSame($expectedName, $actualStringType->getName());
        $this->assertSame($expectedLength, $actualStringType->getLength());
        $this->assertSame($expectedAllowDefault, $actualStringType->doesAllowDefault());
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

    public function timeTypeAsserts(
        $expectedName,
        $expectedPrecision,
        $expectedAllowDefault,
        $valueToCast,
        $expectedCastValue,
        TimeTypeInterface $actualTimeType
    ) {
        $this->assertSame($expectedName, $actualTimeType->getName());
        $this->assertSame($expectedPrecision, $actualTimeType->getPrecision());
        $this->assertSame($expectedAllowDefault, $actualTimeType->doesAllowDefault());
        $this->assertSame($expectedCastValue, $actualTimeType->cast($valueToCast));
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

    /**
     * @param string $expectedName
     * @param DataTypeInterface|string $expectedDataType
     * @param bool $expectedAllowNull
     * @param $expectedHasDefault
     * @param string $expectedDefault
     * @param string $expectedComment
     * @param $expectedZeroFill
     * @param $expectedAutoIncrement
     * @param ColumnInterface $actualColumn
     */
    public function columnAsserts(
        $expectedName,
        $expectedDataType,
        $expectedAllowNull,
        $expectedHasDefault,
        $expectedDefault,
        $expectedComment,
        $expectedZeroFill,
        $expectedAutoIncrement,
        ColumnInterface $actualColumn
    ) {
        $this->assertSame($expectedName, $actualColumn->getName());
        $this->assertSame($expectedDataType, $actualColumn->getDataType());
        $this->assertSame($expectedAllowNull, $actualColumn->doesAllowNull());
        $this->assertSame($expectedHasDefault, $actualColumn->hasDefault());
        $this->assertSame($expectedDefault, $actualColumn->getDefault());
        $this->assertSame($expectedComment, $actualColumn->getComment());
        $this->assertSame($expectedZeroFill, $actualColumn->doesZeroFill());
        $this->assertSame($expectedAutoIncrement, $actualColumn->isAutoIncrement());
    }

    /**
     * @param string $expectedName
     * @param string $expectedType
     * @param ColumnInterface[] $expectedIndexedColumns
     * @param string $expectedIndexType
     * @param string $expectedComment
     * @param IndexInterface $actualIndex
     */
    public function indexAsserts(
        $expectedName,
        $expectedType,
        array $expectedIndexedColumns,
        $expectedIndexType,
        $expectedComment,
        IndexInterface $actualIndex
    ) {
        $this->assertSame($expectedName, $actualIndex->getName());
        $this->assertSame($expectedIndexedColumns, $actualIndex->getIndexedColumns());
        $this->assertSame($expectedIndexType, $actualIndex->getIndexType());
        $this->assertSame($expectedComment, $actualIndex->getComment());

        if ($expectedType !== null) {
            $this->assertSame($expectedType, $actualIndex->getType());
        }
    }
}
