<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/07/16
 * Time: 6:45 PM.
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\BinaryTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\BinaryType;
use PHPUnit\Framework\MockObject\MockObject;

class BinaryTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @var BinaryTypeFactory
     */
    protected $binaryTypeFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->binaryTypeFactory = new BinaryTypeFactory();
    }

    public function testNewType()
    {
        $this->assertInstanceOf(BinaryType::class, $this->binaryTypeFactory->newType());
    }

    /**
     * @dataProvider createTypeProvider
     *
     * @param int    $expectedLength
     * @param string $typeStr
     */
    public function testPopulate($expectedLength, $typeStr)
    {
        $mockBinaryType = $this->createMockBinaryType($expectedLength);

        $this->assertSame($mockBinaryType, $this->binaryTypeFactory->populate($mockBinaryType, $typeStr));
    }

    /**
     * @dataProvider createTypeProvider
     *
     * @param int    $expectedLength
     * @param string $typeStr
     */
    public function testCreate($expectedLength, $typeStr)
    {
        $mockBinaryType = $this->createMockBinaryType($expectedLength);

        /** @var BinaryTypeFactory|MockObject $mockBinaryTypeFactory */
        $mockBinaryTypeFactory = $this->getMockBuilder(BinaryTypeFactory::class)
            ->setMethods(['newType'])
            ->getMock();
        $mockBinaryTypeFactory->method('newType')
            ->willReturn($mockBinaryType);

        $this->assertSame($mockBinaryType, $mockBinaryTypeFactory->createType($typeStr));
    }

    /**
     * @dataProvider doRecogniseProvider
     *
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue($this->binaryTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     *
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse($this->binaryTypeFactory->doRecognise($typeStr));
    }

    public function testCreateWrongType()
    {
        $this->assertFalse($this->binaryTypeFactory->createType('varchar(254)'));
    }

    /**
     * @see testDoRecognise
     *
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['binary(123)'],
            ['BINARY(123)'],
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
            ['binary'],
        ];
    }

    public function createTypeProvider()
    {
        return [
            [
                123,
                'binary(123)',
            ],
            [
                123,
                'BINARY(123)',
            ],
        ];
    }

    /**
     * @param $expectedLength
     *
     * @return BinaryType|MockObject
     */
    protected function createMockBinaryType($expectedLength)
    {
        $mockBinaryType = $this->createMock(BinaryType::class);
        $mockBinaryType->expects($this->once())
            ->method('setLength')
            ->with($expectedLength);

        return $mockBinaryType;
    }
}
