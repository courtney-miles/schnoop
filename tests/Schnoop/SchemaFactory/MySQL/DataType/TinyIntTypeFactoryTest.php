<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\AbstractIntTypeFactoryTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\IntTypeFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\TinyIntTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\TinyIntType;
use PHPUnit\Framework\MockObject\MockObject;

class TinyIntTypeFactoryTest extends AbstractIntTypeFactoryTestCase
{
    /**
     * @return IntTypeFactoryInterface
     */
    protected function newIntTypeFactory()
    {
        return new TinyIntTypeFactory();
    }

    /**
     * @param MockObject $mockIntType
     *
     * @return IntTypeFactoryInterface
     */
    protected function newMockedIntTypeFactory(MockObject $mockIntType = null)
    {
        $mockTinyIntTypeFactory = $this->getMockBuilder(TinyIntTypeFactory::class)
            ->setMethods(['newType'])
            ->getMock();
        $mockTinyIntTypeFactory->method('newType')
            ->willReturn($mockIntType);

        return $mockTinyIntTypeFactory;
    }

    protected function getExpectedIntTypeClass()
    {
        return TinyIntType::class;
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
                'tinyint',
            ],
            [
                4,
                true,
                false,
                'tinyint(4)',
            ],
            [
                null,
                true,
                false,
                'tinyint signed',
            ],
            [
                4,
                true,
                false,
                'tinyint(4) signed',
            ],
            [
                3,
                false,
                false,
                'tinyint(3) unsigned',
            ],
            [
                4,
                true,
                true,
                'tinyint(4) zerofill',
            ],
            [
                4,
                true,
                true,
                'tinyint(4) signed zerofill',
            ],
            [
                4,
                false,
                true,
                'tinyint(4) unsigned zerofill',
            ],
            [
                3,
                false,
                true,
                'TINYINT(3) UNSIGNED ZEROFILL',
            ],
        ];
    }

    /**
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['tinyint'],
            ['tinyint(4)'],
            ['tinyint(4) signed'],
            ['tinyint(4) signed zerofill'],
            ['tinyint(3) unsigned'],
            ['tinyint(3) unsigned zerofill'],
            ['tinyint(4) zerofill'],
            ['TINYINT(3) UNSIGNED ZEROFILL'],
        ];
    }

    /**
     * @return array
     */
    public function doNotRecogniseProvider()
    {
        return [
            ['int(10)'],
            ['varchar(255)'],
        ];
    }
}
