<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\AbstractNumericPointTypeFactoryTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DecimalTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\NumericPointTypeFactoryInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\DecimalType;
use PHPUnit_Framework_MockObject_MockObject;

class DecimalTypeFactoryTest extends AbstractNumericPointTypeFactoryTestCase
{

    /**
     * @return NumericPointTypeFactoryInterface
     */
    protected function newNumericFloatTypeFactory()
    {
        return new DecimalTypeFactory();
    }

    /**
     * @param PHPUnit_Framework_MockObject_MockObject $mockNumericPointType
     * @return NumericPointTypeFactoryInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected function newMockNumericPointTypeFactory(
        PHPUnit_Framework_MockObject_MockObject $mockNumericPointType
    ) {
        $mockDecimalTypeFactory = $this->getMockBuilder(DecimalTypeFactory::class)
            ->setMethods(['newType'])
            ->getMock();
        $mockDecimalTypeFactory->method('newType')
            ->willReturn($mockNumericPointType);

        return $mockDecimalTypeFactory;
    }

    /**
     * @string
     */
    protected function getExpectedNumericPointTypeClass()
    {
        return DecimalType::class;
    }

    /**
     * @return array
     */
    public function populateProvider()
    {
        return [
            [
                true,
                6,
                2,
                false,
                'decimal(6,2)',
            ],
            [
                true,
                6,
                2,
                false,
                'decimal(6,2) signed',
            ],
            [
                false,
                6,
                2,
                false,
                'decimal(6,2) unsigned',
            ],
            [
                true,
                6,
                2,
                true,
                'decimal(6,2) zerofill',
            ],
            [
                true,
                6,
                2,
                true,
                'decimal(6,2) signed zerofill',
            ],
            [
                false,
                6,
                2,
                true,
                'decimal(6,2) unsigned zerofill',
            ],
            [
                false,
                6,
                2,
                true,
                'DECIMAL ( 6 , 2 ) UNSIGNED ZEROFILL',
            ],
        ];
    }

    /**
     * @see testDoRecognise
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['decimal(6,2) unsigned'],
            ['decimal(6,2)']
        ];
    }

    /**
     * @see testDoNotRecognise
     * @return array
     */
    public function doNotRecogniseProvider()
    {
        return [
            ['float'],
            ['varchar(255)'],
        ];
    }
}
