<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\CharTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\CharType;
use PHPUnit\Framework\MockObject\MockObject;

class CharTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @var CharTypeFactory
     */
    protected $charTypeFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->charTypeFactory = new CharTypeFactory();
    }

    public function testNewType()
    {
        $this->assertInstanceOf(CharType::class, $this->charTypeFactory->newType());
    }

    /**
     * @dataProvider createTypeProvider
     *
     * @param $expectedLength
     * @param $expectedCollation
     * @param $typeStr
     */
    public function testPopulate($expectedLength, $expectedCollation, $typeStr)
    {
        $mockCharType = $this->createMockCharType($expectedLength, $expectedCollation);

        $this->assertSame($mockCharType, $this->charTypeFactory->populate($mockCharType, $typeStr, $expectedCollation));
    }

    /**
     * @dataProvider createTypeProvider
     *
     * @param $expectedLength
     * @param $expectedCollation
     * @param $typeStr
     */
    public function testCreate($expectedLength, $expectedCollation, $typeStr)
    {
        $mockCharType = $this->createMockCharType($expectedLength, $expectedCollation);

        /** @var CharTypeFactory|MockObject $mockCharTypeFactory */
        $mockCharTypeFactory = $this->getMockBuilder(CharTypeFactory::class)
            ->setMethods(['newType'])
            ->getMock();
        $mockCharTypeFactory->method('newType')
            ->willReturn($mockCharType);

        $this->assertSame($mockCharType, $mockCharTypeFactory->createType($typeStr, $expectedCollation));
    }

    /**
     * @dataProvider doRecogniseProvider
     *
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue($this->charTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     *
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse($this->charTypeFactory->doRecognise($typeStr));
    }

    public function testCreateWrongType()
    {
        $this->assertFalse($this->charTypeFactory->createType('varchar(254)'));
    }

    /**
     * @see testDoRecognise
     *
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['char(123)'],
            ['CHAR(123)'],
        ];
    }

    /**
     * @see testDoNotRecognise
     *
     * @return array
     */
    public function doNotRecogniseProvider()
    {
        return [
            ['varchar(255)'],
            ['char'],
        ];
    }

    public function createTypeProvider()
    {
        return [
            [
                123,
                'utf8_general_ci',
                'char(123)',
            ],
            [
                123,
                'utf8_general_ci',
                'CHAR(123)',
            ],
        ];
    }

    /**
     * @param $expectedLength
     * @param $expectedCollation
     *
     * @return CharType|MockObject
     */
    protected function createMockCharType($expectedLength, $expectedCollation)
    {
        $mockCharType = $this->createMock(CharType::class);
        $mockCharType->expects($this->once())
            ->method('setLength')
            ->with($expectedLength);
        $mockCharType->expects($this->once())
            ->method('setCollation')
            ->with($expectedCollation);

        return $mockCharType;
    }
}
