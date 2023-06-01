<?php

namespace MilesAsylum\Schnoop\PHPUnit\Framework;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\IntTypeFactoryInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\IntTypeInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

abstract class AbstractIntTypeFactoryTestCase extends TestCase
{
    /**
     * @var IntTypeFactoryInterface
     */
    private $intTypeFactory;

    /**
     * @return IntTypeFactoryInterface
     */
    abstract protected function newIntTypeFactory();

    /**
     * @param MockObject $mockIntType
     *
     * @return IntTypeFactoryInterface
     */
    abstract protected function newMockedIntTypeFactory(MockObject $mockIntType = null);

    /**
     * @return string
     */
    abstract protected function getExpectedIntTypeClass();

    /**
     * @return array
     */
    abstract public function populateProvider();

    /**
     * @return array
     */
    abstract public function doRecogniseProvider();

    /**
     * @return array
     */
    abstract public function doNotRecogniseProvider();

    protected function setUp(): void
    {
        parent::setUp();

        $this->intTypeFactory = $this->newIntTypeFactory();
    }

    public function testNewType()
    {
        $this->assertInstanceOf($this->getExpectedIntTypeClass(), $this->intTypeFactory->newType());
    }

    /**
     * @dataProvider populateProvider
     *
     * @param int    $expectedDisplayWidth
     * @param bool   $expectedSigned
     * @param bool   $expectedZeroFill
     * @param string $typeStr
     */
    public function testPopulate($expectedDisplayWidth, $expectedSigned, $expectedZeroFill, $typeStr)
    {
        $mockIntType = $this->createMockIntType($expectedDisplayWidth, $expectedSigned, $expectedZeroFill);

        $this->assertSame(
            $mockIntType,
            $this->intTypeFactory->populate($mockIntType, $typeStr),
            "Supplied string was '$typeStr'."
        );
    }

    /**
     * @dataProvider populateProvider
     *
     * @param int    $expectedDisplayWidth
     * @param bool   $expectedSigned
     * @param bool   $expectedZeroFill
     * @param string $typeStr
     */
    public function testCreate($expectedDisplayWidth, $expectedSigned, $expectedZeroFill, $typeStr)
    {
        $mockIntType = $this->createMockIntType($expectedDisplayWidth, $expectedSigned, $expectedZeroFill);

        $intTypeFactory = $this->newMockedIntTypeFactory($mockIntType);

        $this->assertSame(
            $mockIntType,
            $intTypeFactory->createType($typeStr),
            "Supplied string was '$typeStr'."
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse($this->intTypeFactory->createType('bogus'));
    }

    /**
     * @dataProvider doRecogniseProvider
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue($this->intTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse($this->intTypeFactory->doRecognise($typeStr));
    }

    /**
     * @param int  $expectedDisplayWidth
     * @param bool $expectedSigned
     * @param bool $expectedZeroFill
     *
     * @return IntTypeInterface|MockObject
     */
    protected function createMockIntType($expectedDisplayWidth, $expectedSigned, $expectedZeroFill)
    {
        $mockIntType = $this->createMock(IntTypeInterface::class);
        $mockIntType->expects($this->once())
            ->method('setDisplayWidth')
            ->with($expectedDisplayWidth);
        $mockIntType->expects($this->once())
            ->method('setSigned')
            ->with($expectedSigned);
        $mockIntType->expects($this->once())
            ->method('setZeroFill')
            ->with($expectedZeroFill);

        return $mockIntType;
    }
}
