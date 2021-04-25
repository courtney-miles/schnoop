<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\AbstractIntTypeFactoryTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\IntTypeFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\MediumIntTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\MediumIntType;
use PHPUnit\Framework\MockObject\MockObject;

class MediumIntTypeFactoryTest extends AbstractIntTypeFactoryTestCase
{

    /**
     * @return IntTypeFactoryInterface
     */
    protected function newIntTypeFactory()
    {
        return new MediumIntTypeFactory();
    }

    /**
     * @param MockObject $mockIntType
     * @return IntTypeFactoryInterface
     */
    protected function newMockedIntTypeFactory(MockObject $mockIntType = null)
    {
        $mockMediumIntTypeFactory = $this->getMockBuilder(MediumIntTypeFactory::class)
            ->setMethods(['newType'])
            ->getMock();
        $mockMediumIntTypeFactory->method('newType')
            ->willReturn($mockIntType);

        return $mockMediumIntTypeFactory;
    }

    protected function getExpectedIntTypeClass()
    {
        return MediumIntType::class;
    }

    /**
     * @return array
     */
    public function populateProvider()
    {
        return [
            [
                11,
                true,
                false,
                'mediumint(11)'
            ],
            [
                11,
                true,
                false,
                'mediumint(11) signed'
            ],
            [
                10,
                false,
                false,
                'mediumint(10) unsigned'
            ],
            [
                11,
                true,
                true,
                'mediumint(11) zerofill'
            ],
            [
                11,
                true,
                true,
                'mediumint(11) signed zerofill'
            ],
            [
                11,
                false,
                true,
                'mediumint(11) unsigned zerofill'
            ],
            [
                10,
                false,
                true,
                'MEDIUMINT(10) UNSIGNED ZEROFILL'
            ],
        ];
    }

    /**
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['mediumint(10)'],
            ['mediumint(10) signed'],
            ['mediumint(10) signed zerofill'],
            ['mediumint(10) unsigned'],
            ['mediumint(10) unsigned zerofill'],
            ['mediumint(10) zerofill'],
            ['MEDIUMINT(10) UNSIGNED ZEROFILL'],
        ];
    }

    /**
     * @return array
     */
    public function doNotRecogniseProvider()
    {
        return [
            ['mediumint'],
            ['int(10)'],
            ['varchar(255)']
        ];
    }
}
