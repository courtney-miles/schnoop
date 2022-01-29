<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/07/16
 * Time: 6:50 PM.
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\AbstractNumericPointTypeFactoryTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\FloatTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\NumericPointTypeFactoryInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\FloatType;
use PHPUnit\Framework\MockObject\MockObject;

class FloatTypeFactoryTest extends AbstractNumericPointTypeFactoryTestCase
{
    /**
     * @return NumericPointTypeFactoryInterface
     */
    protected function newNumericFloatTypeFactory()
    {
        return new FloatTypeFactory();
    }

    /**
     * @return NumericPointTypeFactoryInterface|MockObject
     */
    protected function newMockNumericPointTypeFactory(
        MockObject $mockNumericPointType
    ) {
        $mockFloatTypeFactory = $this->getMockBuilder(FloatTypeFactory::class)
            ->setMethods(['newType'])
            ->getMock();
        $mockFloatTypeFactory->method('newType')
            ->willReturn($mockNumericPointType);

        return $mockFloatTypeFactory;
    }

    /**
     * @string
     */
    protected function getExpectedNumericPointTypeClass()
    {
        return FloatType::class;
    }

    /**
     * @return array
     */
    public function populateProvider()
    {
        return [
            [
                true,
                null,
                null,
                false,
                'float',
            ],
            [
                true,
                null,
                null,
                false,
                'float signed',
            ],
            [
                true,
                6,
                2,
                false,
                'float(6,2)',
            ],
            [
                true,
                6,
                2,
                false,
                'float(6,2) signed',
            ],
            [
                false,
                6,
                2,
                false,
                'float(6,2) unsigned',
            ],
            [
                true,
                6,
                2,
                true,
                'float(6,2) zerofill',
            ],
            [
                false,
                6,
                2,
                true,
                'float(6,2) unsigned zerofill',
            ],
        ];
    }

    /**
     * @see testDoRecognise
     *
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['float(6,2) unsigned'],
            ['float(6,2)'],
            ['float unsigned'],
            ['float'],
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
        ];
    }
}
