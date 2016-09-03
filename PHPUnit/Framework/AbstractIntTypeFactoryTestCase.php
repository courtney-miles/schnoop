<?php

namespace MilesAsylum\Schnoop\PHPUnit\Framework;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\IntTypeFactoryInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\IntTypeInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

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
     * @param PHPUnit_Framework_MockObject_MockObject $mockIntType
     * @return IntTypeFactoryInterface
     */
    abstract protected function newMockedIntTypeFactory(PHPUnit_Framework_MockObject_MockObject $mockIntType = null);

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

    protected function setUp()
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
     * @param int $expectedDisplayWidth
     * @param bool $expectedSigned
     * @param bool $expectedZeroFill
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
     * @param int $expectedDisplayWidth
     * @param bool $expectedSigned
     * @param bool $expectedZeroFill
     * @param string $typeStr
     */
    public function testCreate($expectedDisplayWidth, $expectedSigned, $expectedZeroFill, $typeStr)
    {
        $mockIntType = $this->createMockIntType($expectedDisplayWidth, $expectedSigned, $expectedZeroFill);

        $intTypeFactory = $this->newMockedIntTypeFactory($mockIntType);

        $this->assertSame(
            $mockIntType,
            $intTypeFactory->create($typeStr),
            "Supplied string was '$typeStr'."
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse($this->intTypeFactory->create('bogus'));
    }

    /**
     * @dataProvider doRecogniseProvider
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue($this->intTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse($this->intTypeFactory->doRecognise($typeStr));
    }

    /**
     * @param int $expectedDisplayWidth
     * @param bool $expectedSigned
     * @param bool $expectedZeroFill
     * @return IntTypeInterface|PHPUnit_Framework_MockObject_MockObject
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
