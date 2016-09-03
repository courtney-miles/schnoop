<?php

namespace MilesAsylum\Schnoop\PHPUnit\Framework;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\NumericPointTypeFactoryInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\NumericPointTypeInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

abstract class AbstractNumericPointTypeFactoryTestCase extends TestCase
{
    /**
     * @var NumericPointTypeFactoryInterface
     */
    private $numericPointTypeFactory;

    /**
     * @return NumericPointTypeFactoryInterface
     */
    abstract protected function newNumericFloatTypeFactory();

    /**
     * @param PHPUnit_Framework_MockObject_MockObject $mockNumericPointType
     * @return NumericPointTypeFactoryInterface|PHPUnit_Framework_MockObject_MockObject
     */
    abstract protected function newMockNumericPointTypeFactory(
        PHPUnit_Framework_MockObject_MockObject $mockNumericPointType
    );

    /**
     * @string
     */
    abstract protected function getExpectedNumericPointTypeClass();

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

        $this->numericPointTypeFactory = $this->newNumericFloatTypeFactory();
    }

    public function testNewType()
    {
        $this->assertInstanceOf($this->getExpectedNumericPointTypeClass(), $this->numericPointTypeFactory->newType());
    }

    /**
     * @dataProvider populateProvider
     * @param bool $expectedSigned
     * @param int|null $expectedPrecision
     * @param int|null $expectedScale
     * @param bool $expectedZeroFill
     * @param string $typeStr
     */
    public function testPopulate(
        $expectedSigned,
        $expectedPrecision,
        $expectedScale,
        $expectedZeroFill,
        $typeStr
    ) {
        $mockNumericPointType = $this->createMockNumericPointType(
            $expectedSigned,
            $expectedPrecision,
            $expectedScale,
            $expectedZeroFill
        );

        $this->assertSame(
            $mockNumericPointType,
            $this->numericPointTypeFactory->populate($mockNumericPointType, $typeStr)
        );
    }

    /**
     * @dataProvider populateProvider
     * @param bool $expectedSigned
     * @param int|null $expectedPrecision
     * @param int|null $expectedScale
     * @param bool $expectedZeroFill
     * @param string $typeStr
     */
    public function testCreate(
        $expectedSigned,
        $expectedPrecision,
        $expectedScale,
        $expectedZeroFill,
        $typeStr
    ) {
        $mockNumericPointType = $this->createMockNumericPointType(
            $expectedSigned,
            $expectedPrecision,
            $expectedScale,
            $expectedZeroFill
        );

        $mockNumericPointTypeFactory = $this->newMockNumericPointTypeFactory($mockNumericPointType);

        $this->assertSame($mockNumericPointType, $mockNumericPointTypeFactory->create($typeStr));
    }


    public function testCreateWrongType()
    {
        $this->assertFalse($this->numericPointTypeFactory->create('bogus'));
    }

    /**
     * @dataProvider doRecogniseProvider
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue($this->numericPointTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse($this->numericPointTypeFactory->doRecognise($typeStr));
    }


    /**
     * @param bool $expectedSigned
     * @param int|null $expectedPrecision
     * @param int|null $expectedScale
     * @param bool $expectedZeroFill
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function createMockNumericPointType(
        $expectedSigned,
        $expectedPrecision,
        $expectedScale,
        $expectedZeroFill
    ) {
        $mockNumericPointType = $this->createMock(NumericPointTypeInterface::class);
        $mockNumericPointType->expects($this->once())
            ->method('setSigned')
            ->with($expectedSigned);
        $mockNumericPointType->expects($this->once())
            ->method('setPrecisionScale')
            ->with($expectedPrecision, $expectedScale);
        $mockNumericPointType->expects($this->once())
            ->method('setZeroFill')
            ->with($expectedZeroFill);

        return $mockNumericPointType;
    }
}
