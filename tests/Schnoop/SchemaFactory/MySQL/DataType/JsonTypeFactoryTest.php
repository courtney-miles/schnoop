<?php

declare(strict_types=1);

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\JsonTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\JsonType;

/**
 * @covers \MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\JsonTypeFactory
 */
class JsonTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @dataProvider provideJsonTypeStrings
     */
    public function testDoRecogniseType(string $typeString): void
    {
        $sut = new JsonTypeFactory();

        self::assertTrue($sut->doRecognise($typeString));
    }

    public function provideJsonTypeStrings(): array
    {
        return [
            ['json'],
            ['JSON'],
        ];
    }

    public function testDoNotRecogniseType(): void
    {
        $sut = new JsonTypeFactory();

        self::assertFalse($sut->doRecognise('not_json'));
    }

    /**
     * @dataProvider provideTypeStrings
     */
    public function testCreateType(string $typeString): void
    {
        $sut = new JsonTypeFactory();

        self::assertInstanceOf(
            JsonType::class,
            $sut->createType($typeString)
        );
    }

    public function provideTypeStrings(): array
    {
        return [
            ['JSON'],
            ['json'],
        ];
    }

    public function testCreateTypeWrongString(): void
    {
        $sut = new JsonTypeFactory();

        self::assertFalse($sut->createType('BOGUS'));
    }
}
