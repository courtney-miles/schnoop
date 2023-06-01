<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\VarBinaryTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\VarBinaryType;
use PHPUnit\Framework\MockObject\MockObject;

class VarBinaryTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @var VarBinaryTypeFactory
     */
    protected $varBinaryTypeFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->varBinaryTypeFactory = new VarBinaryTypeFactory();
    }

    public function testNewType()
    {
        $length = 10;

        $varBinaryType = $this->varBinaryTypeFactory->newType($length);

        $this->assertInstanceOf(VarBinaryType::class, $varBinaryType);
        $this->assertSame($length, $varBinaryType->getLength());
    }

    /**
     * @dataProvider createTypeProvider
     */
    public function testCreate($typeStr)
    {
        $mockVarBinaryType = $this->createMock(VarBinaryType::class);

        /** @var VarBinaryTypeFactory|MockObject $mockVarBinaryTypeFactory */
        $mockVarBinaryTypeFactory = $this->getMockBuilder(VarBinaryTypeFactory::class)
            ->setMethods(['newType'])
            ->getMock();
        $mockVarBinaryTypeFactory->method('newType')
            ->willReturn($mockVarBinaryType);

        $this->assertSame($mockVarBinaryType, $mockVarBinaryTypeFactory->createType($typeStr));
    }

    /**
     * @dataProvider doRecogniseProvider
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue($this->varBinaryTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse($this->varBinaryTypeFactory->doRecognise($typeStr));
    }

    public function testCreateWrongType()
    {
        $this->assertFalse($this->varBinaryTypeFactory->createType('varchar(254)'));
    }

    /**
     * @see testDoRecognise
     *
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['varbinary(123)'],
            ['VARBINARY(123)'],
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
            ['varbinary'],
        ];
    }

    public function createTypeProvider()
    {
        return [
            [
                'varbinary(123)',
            ],
            [
                'VARBINARY(123)',
            ],
        ];
    }
}
