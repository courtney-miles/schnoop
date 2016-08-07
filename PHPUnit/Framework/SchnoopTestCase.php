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
     * @param string $expectedType
     * @param int $expectedDisplayWidth
     * @param bool $expectedSigned
     * @param int $expectedMinRange
     * @param int $expectedMaxRange
     * @param bool $expectedAllowDefault
     * @param string $expectedDDL
     * @param IntTypeInterface $actualIntType
     */
    public function intTypeAsserts(
        $expectedType,
        $expectedDisplayWidth,
        $expectedSigned,
        $expectedMinRange,
        $expectedMaxRange,
        $expectedAllowDefault,
        $expectedDDL,
        IntTypeInterface $actualIntType
    ) {
        $this->assertSame($expectedType, $actualIntType->getType());
        $this->assertSame($expectedDisplayWidth, $actualIntType->getDisplayWidth());
        $this->assertSame($expectedSigned, $actualIntType->isSigned());
        $this->assertSame($expectedMinRange, $actualIntType->getMinRange());
        $this->assertSame($expectedMaxRange, $actualIntType->getMaxRange());
        $this->assertSame($expectedAllowDefault, $actualIntType->doesAllowDefault());
        $this->assertSame($expectedDDL, (string)$actualIntType);
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
     * @param string $expectedType
     * @param bool $expectedSigned
     * @param int|null $expectedPrecision
     * @param int|null $expectedScale
     * @param string|null $expectedMinRange
     * @param string|null $expectedMaxRange
     * @param bool $expectedAllowDefault
     * @param string $expectedDDL
     * @param NumericPointTypeInterface $actualNumericPointType
     */
    public function numericPointTypeAsserts(
        $expectedType,
        $expectedSigned,
        $expectedPrecision,
        $expectedScale,
        $expectedMinRange,
        $expectedMaxRange,
        $expectedAllowDefault,
        $expectedDDL,
        NumericPointTypeInterface $actualNumericPointType
    ) {
        $this->assertSame($expectedType, $actualNumericPointType->getType());
        $this->assertSame($expectedPrecision, $actualNumericPointType->getPrecision());
        $this->assertSame($expectedScale, $actualNumericPointType->getScale());
        $this->assertSame($expectedSigned, $actualNumericPointType->isSigned());
        $this->assertSame($expectedMinRange, $actualNumericPointType->getMinRange());
        $this->assertSame($expectedMaxRange, $actualNumericPointType->getMaxRange());
        $this->assertSame($expectedAllowDefault, $actualNumericPointType->doesAllowDefault());
        $this->assertSame($expectedDDL, (string)$actualNumericPointType);
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
     * @param string $expectedType
     * @param int $expectedLength
     * @param string|null $expectedCollation
     * @param bool $expectedAllowDefault
     * @param string $expectedDDL
     * @param StringTypeInterface $actualStringType
     */
    public function stringTypeAsserts(
        $expectedType,
        $expectedLength,
        $expectedCollation,
        $expectedAllowDefault,
        $expectedDDL,
        StringTypeInterface $actualStringType
    ) {
        $this->assertSame($expectedType, $actualStringType->getType());
        $this->assertSame($expectedLength, $actualStringType->getLength());
        $this->assertSame($expectedCollation, $actualStringType->getCollation());
        $this->assertSame($expectedAllowDefault, $actualStringType->doesAllowDefault());
        $this->assertSame($expectedDDL, (string)$actualStringType);
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
     * @param string $expectedType
     * @param int $expectedLength
     * @param bool $expectedAllowDefault
     * @param string $expectedDDL
     * @param BinaryTypeInterface $actualStringType
     */
    public function binaryTypeAsserts(
        $expectedType,
        $expectedLength,
        $expectedAllowDefault,
        $expectedDDL,
        BinaryTypeInterface $actualStringType
    ) {
        $this->assertSame($expectedType, $actualStringType->getType());
        $this->assertSame($expectedLength, $actualStringType->getLength());
        $this->assertSame($expectedAllowDefault, $actualStringType->doesAllowDefault());
        $this->assertSame($expectedDDL, (string)$actualStringType);
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
        $expectedType,
        $expectedPrecision,
        $expectedAllowDefault,
        $expectedDDL,
        TimeTypeInterface $actualTimeType
    ) {
        $this->assertSame($expectedType, $actualTimeType->getType());
        $this->assertSame($expectedPrecision, $actualTimeType->getPrecision());
        $this->assertSame($expectedAllowDefault, $actualTimeType->doesAllowDefault());
        $this->assertSame($expectedDDL, (string)$actualTimeType);
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
     * @param string $expectedDDL
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
        $expectedDDL,
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
        $this->assertSame($expectedDDL, (string)$actualColumn);
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
