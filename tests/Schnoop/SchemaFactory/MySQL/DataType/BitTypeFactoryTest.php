<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/07/16
 * Time: 7:13 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\BitTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\BitType;
use PHPUnit\Framework\MockObject\MockObject;

class BitTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @var BitTypeFactory
     */
    protected $bitTypeFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->bitTypeFactory = new BitTypeFactory();
    }

    public function testNewType()
    {
        $this->assertInstanceOf(BitType::class, $this->bitTypeFactory->newType());
    }

    /**
     * @dataProvider createTypeProvider
     * @param int $expectedLength
     * @param string $typeStr
     */
    public function testPopulate($expectedLength, $typeStr)
    {
        $mockBitType = $this->createMockBitType($expectedLength);

        $this->assertSame($mockBitType, $this->bitTypeFactory->populate($mockBitType, $typeStr));
    }

    /**
     * @dataProvider createTypeProvider
     * @param int $expectedLength
     * @param string $typeStr
     */
    public function testCreate($expectedLength, $typeStr)
    {
        $mockBitType = $this->createMockBitType($expectedLength);

        /** @var BitTypeFactory|PHPUnit\Framework\MockObject\MockObject $mockBitTypeFactory */
        $mockBitTypeFactory = $this->getMockBuilder(BitTypeFactory::class)
            ->setMethods(['newType'])
            ->getMock();
        $mockBitTypeFactory->method('newType')
            ->willReturn($mockBitType);

        $this->assertSame($mockBitType, $mockBitTypeFactory->createType($typeStr));
    }

    /**
     * @dataProvider doRecogniseProvider
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue($this->bitTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse($this->bitTypeFactory->doRecognise($typeStr));
    }

    public function testCreateWrongType()
    {
        $this->assertFalse($this->bitTypeFactory->createType('varchar(254)'));
    }

    /**
     * @see testDoRecognise
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['bit(123)'],
            ['BIT(123)'],
        ];
    }

    /**
     * @see testDoNotRecognise
     * @return array
     */
    public function doNotRecogniseProvider()
    {
        return [
            ['varchar(255)'],
            ['bit']
        ];
    }

    public function createTypeProvider()
    {
        return [
            [
                123,
                'bit(123)',
            ],
            [
                123,
                'BIT(123)',
            ]
        ];
    }

    /**
     * @param $expectedLength
     * @return BitType|MockObject
     */
    protected function createMockBitType($expectedLength)
    {
        $mockBitType = $this->createMock(BitType::class);
        $mockBitType->expects($this->once())
            ->method('setLength')
            ->with($expectedLength);

        return $mockBitType;
    }
}
