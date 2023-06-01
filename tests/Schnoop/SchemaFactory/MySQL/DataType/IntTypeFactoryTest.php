<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\AbstractIntTypeFactoryTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\IntTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\IntTypeFactoryInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\IntType;
use PHPUnit\Framework\MockObject\MockObject;

class IntTypeFactoryTest extends AbstractIntTypeFactoryTestCase
{
    /**
     * @return IntTypeFactoryInterface
     */
    protected function newIntTypeFactory()
    {
        return new IntTypeFactory();
    }

    protected function newMockedIntTypeFactory(MockObject $mockIntType = null)
    {
        $intTypeFactory = $this->getMockBuilder(IntTypeFactory::class)
            ->setMethods(['newType'])
            ->getMock();
        $intTypeFactory->method('newType')
            ->willReturn($mockIntType);

        return $intTypeFactory;
    }

    protected function getExpectedIntTypeClass()
    {
        return IntType::class;
    }

    /**
     * @return array
     */
    public function populateProvider()
    {
        return [
            [
                null,
                true,
                false,
                'int',
            ],
            [
                11,
                true,
                false,
                'int(11)',
            ],
            [
                null,
                true,
                false,
                'int signed',
            ],
            [
                11,
                true,
                false,
                'int(11) signed',
            ],
            [
                10,
                false,
                false,
                'int(10) unsigned',
            ],
            [
                11,
                true,
                true,
                'int(11) zerofill',
            ],
            [
                11,
                true,
                true,
                'int(11) signed zerofill',
            ],
            [
                11,
                false,
                true,
                'int(11) unsigned zerofill',
            ],
            [
                10,
                false,
                true,
                'INT(10) UNSIGNED ZEROFILL',
            ],
        ];
    }

    /**
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['int'],
            ['int(10)'],
            ['int(10) signed'],
            ['int(10) signed zerofill'],
            ['int(10) unsigned'],
            ['int(10) unsigned zerofill'],
            ['int(10) zerofill'],
            ['INT(10) UNSIGNED ZEROFILL'],
        ];
    }

    /**
     * @return array
     */
    public function doNotRecogniseProvider()
    {
        return [
            ['bigint(10)'],
            ['varchar(255)'],
        ];
    }
}
