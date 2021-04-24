<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/07/16
 * Time: 8:16 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\AbstractNumericPointTypeFactoryTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DoubleTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\NumericPointTypeFactoryInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\DoubleType;
use PHPUnit\Framework\MockObject\MockObject;

class DoubleTypeFactoryTest extends AbstractNumericPointTypeFactoryTestCase
{
    /**
     * @return NumericPointTypeFactoryInterface
     */
    protected function newNumericFloatTypeFactory()
    {
        return new DoubleTypeFactory();
    }

    /**
     * @param MockObject $mockNumericPointType
     * @return NumericPointTypeFactoryInterface|MockObject
     */
    protected function newMockNumericPointTypeFactory(
        MockObject $mockNumericPointType
    ) {
        $mockDoubleTypeFactory = $this->getMockBuilder(DoubleTypeFactory::class)
            ->setMethods(['newType'])
            ->getMock();
        $mockDoubleTypeFactory->method('newType')
            ->willReturn($mockNumericPointType);

        return$mockDoubleTypeFactory;
    }

    /**
     * @string
     */
    protected function getExpectedNumericPointTypeClass()
    {
        return DoubleType::class;
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
                'double'
            ],
            [
                true,
                6,
                2,
                false,
                'double(6,2)'
            ],
            [
                true,
                null,
                null,
                false,
                'double signed'
            ],
            [
                false,
                null,
                null,
                false,
                'double unsigned'
            ],
            [
                true,
                null,
                null,
                true,
                'double zerofill'
            ],
            [
                true,
                null,
                null,
                true,
                'double signed zerofill'
            ],
            [
                false,
                null,
                null,
                true,
                'double unsigned zerofill'
            ],
            [
                false,
                6,
                2,
                true,
                'DOUBLE ( 6 , 2 ) UNSIGNED ZEROFILL'
            ]
        ];
    }

    /**
     * @see testDoRecognise
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['double(6,2) unsigned'],
            ['double(6,2)'],
            ['double unsigned'],
            ['double']
        ];
    }

    /**
     * @see testDoNotRecognise
     * @return array
     */
    public function doNotRecogniseProvider()
    {
        return [
            ['varchar(255)']
        ];
    }
}
