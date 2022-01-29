<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\AbstractIntTypeFactoryTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\BigIntTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\BigIntType;
use PHPUnit\Framework\MockObject\MockObject;

class BigIntTypeFactoryTest extends AbstractIntTypeFactoryTestCase
{
    protected function newIntTypeFactory()
    {
        return new BigIntTypeFactory();
    }

    protected function newMockedIntTypeFactory(MockObject $mockIntType = null)
    {
        $bigIntTypeFactory = $this->getMockBuilder(BigIntTypeFactory::class)
            ->setMethods(['newType'])
            ->getMock();
        $bigIntTypeFactory->method('newType')
            ->willReturn($mockIntType);

        return $bigIntTypeFactory;
    }

    protected function getExpectedIntTypeClass()
    {
        return BigIntType::class;
    }

    public function populateProvider()
    {
        return [
            [
                11,
                true,
                false,
                'bigint(11)',
            ],
            [
                11,
                true,
                false,
                'bigint(11) signed',
            ],
            [
                10,
                false,
                false,
                'bigint(10) unsigned',
            ],
            [
                11,
                true,
                true,
                'bigint(11) zerofill',
            ],
            [
                11,
                true,
                true,
                'bigint(11) signed zerofill',
            ],
            [
                11,
                false,
                true,
                'bigint(11) unsigned zerofill',
            ],
            [
                10,
                false,
                true,
                'BIGINT(10) UNSIGNED ZEROFILL',
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
            ['bigint(10)'],
            ['bigint(10) signed'],
            ['bigint(10) signed zerofill'],
            ['bigint(10) unsigned'],
            ['bigint(10) unsigned zerofill'],
            ['bigint(10) zerofill'],
            ['BIGINT(10) UNSIGNED ZEROFILL'],
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
            ['bigint'],
            ['int(10)'],
            ['varchar(255)'],
        ];
    }
}
