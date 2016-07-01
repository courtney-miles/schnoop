<?php

namespace MilesAsylum\Schnoop\PHPUnit\Framework;

use MilesAsylum\Schnoop\PHPUnit\Framework\Constraint\IsBinaryTypeConstruct;
use MilesAsylum\Schnoop\PHPUnit\Framework\Constraint\IsColumnConstruct;
use MilesAsylum\Schnoop\PHPUnit\Framework\Constraint\IsStringTypeConstruct;
use MilesAsylum\Schnoop\PHPUnit\Framework\Constraint\IsIntTypeConstruct;
use MilesAsylum\Schnoop\PHPUnit\Framework\Constraint\IsNumericPointTypeConstruct;
use MilesAsylum\Schnoop\Schema\MySQL\Column\ColumnInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\BinaryTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\IntTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\NumericPointTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\StringTypeInterface;
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
     * @param IntTypeInterface $actualIntType
     */
    public function assertIsIntTypeConstruct(
        $expectedType,
        $expectedDisplayWidth,
        $expectedSigned,
        $expectedMinRange,
        $expectedMaxRange,
        $expectedAllowDefault,
        IntTypeInterface $actualIntType
    ) {
        $this->assertThat(
            $actualIntType,
            new IsIntTypeConstruct(
                $expectedType,
                $expectedDisplayWidth,
                $expectedSigned,
                $expectedMinRange,
                $expectedMaxRange,
                $expectedAllowDefault
            )
        );
    }

    /**
     * @param string $expectedType
     * @param int $expectedPrecision
     * @param int $expectedScale
     * @param bool $expectedSigned
     * @param string $expectedMinRange
     * @param string $expectedMaxRange
     * @param NumericPointTypeInterface $actualNumericPointType
     */
    public function assertIsNumericPointTypeConstruct(
        $expectedType,
        $expectedPrecision,
        $expectedScale,
        $expectedSigned,
        $expectedMinRange,
        $expectedMaxRange,
        $expectedAllowNull,
        NumericPointTypeInterface $actualNumericPointType
    ) {
        $this->assertThat(
            $actualNumericPointType,
            new IsNumericPointTypeConstruct(
                $expectedType,
                $expectedPrecision,
                $expectedScale,
                $expectedSigned,
                $expectedMinRange,
                $expectedMaxRange,
                $expectedAllowNull
            )
        );
    }

    /**
     * @param string $expectedType
     * @param int $expectedLength
     * @param string $expectedCharacterSet
     * @param string $expectedCollation
     * @param bool $allowDefault
     * @param StringTypeInterface $actualStringType
     */
    public function assertIsStringTypeConstruct(
        $expectedType,
        $expectedLength,
        $expectedCharacterSet,
        $expectedCollation,
        $allowDefault,
        StringTypeInterface $actualStringType
    ) {
        $this->assertThat(
            $actualStringType,
            new IsStringTypeConstruct(
                $expectedType,
                $expectedLength,
                $expectedCharacterSet,
                $expectedCollation,
                $allowDefault
            )
        );
    }

    /**
     * @param string $expectedType
     * @param int $expectedLength
     * @param BinaryTypeInterface $actualStringType
     */
    public function assertIsBinaryTypeConstruct(
        $expectedType,
        $expectedLength,
        $expectedAllowDefault,
        BinaryTypeInterface $actualStringType
    ) {
        $this->assertThat(
            $actualStringType,
            new IsBinaryTypeConstruct(
                $expectedType,
                $expectedLength,
                $expectedAllowDefault
            )
        );
    }

    /**
     * @param string $expectedName
     * @param DataTypeInterface|string $expectedDataType
     * @param bool $expectedAllowNull
     * @param string $expectedDefault
     * @param string $expectedComment
     * @param ColumnInterface $actualColumn
     */
    public function assertIsColumnConstruct(
        $expectedName,
        $expectedDataType,
        $expectedAllowNull,
        $expectedHasDefault,
        $expectedDefault,
        $expectedComment,
        ColumnInterface $actualColumn
    ) {
        $this->assertThat(
            $actualColumn,
            new IsColumnConstruct(
                $expectedName,
                $expectedDataType,
                $expectedAllowNull,
                $expectedHasDefault,
                $expectedDefault,
                $expectedComment
            )
        );
    }
}