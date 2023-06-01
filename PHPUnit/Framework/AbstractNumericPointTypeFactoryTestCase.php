<?php

namespace MilesAsylum\Schnoop\PHPUnit\Framework;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\NumericPointTypeFactoryInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\NumericPointTypeInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

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
     * @return NumericPointTypeFactoryInterface|MockObject
     */
    abstract protected function newMockNumericPointTypeFactory(
        MockObject $mockNumericPointType
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

    protected function setUp(): void
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
     *
     * @param bool     $expectedSigned
     * @param int|null $expectedPrecision
     * @param int|null $expectedScale
     * @param bool     $expectedZeroFill
     * @param string   $typeStr
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
     *
     * @param bool     $expectedSigned
     * @param int|null $expectedPrecision
     * @param int|null $expectedScale
     * @param bool     $expectedZeroFill
     * @param string   $typeStr
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

        $this->assertSame($mockNumericPointType, $mockNumericPointTypeFactory->createType($typeStr));
    }

    public function testCreateWrongType()
    {
        $this->assertFalse($this->numericPointTypeFactory->createType('bogus'));
    }

    /**
     * @dataProvider doRecogniseProvider
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue($this->numericPointTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse($this->numericPointTypeFactory->doRecognise($typeStr));
    }

    /**
     * @param bool     $expectedSigned
     * @param int|null $expectedPrecision
     * @param int|null $expectedScale
     * @param bool     $expectedZeroFill
     *
     * @return NumericPointTypeInterface|MockObject
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
