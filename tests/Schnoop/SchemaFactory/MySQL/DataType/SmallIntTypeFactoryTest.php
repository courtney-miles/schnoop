<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/07/16
 * Time: 7:16 AM.
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\AbstractIntTypeFactoryTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\IntTypeFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\SmallIntTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\SmallIntType;
use PHPUnit\Framework\MockObject\MockObject;

class SmallIntTypeFactoryTest extends AbstractIntTypeFactoryTestCase
{
    /**
     * @return IntTypeFactoryInterface
     */
    protected function newIntTypeFactory()
    {
        return new SmallIntTypeFactory();
    }

    /**
     * @param MockObject $mockIntType
     *
     * @return IntTypeFactoryInterface
     */
    protected function newMockedIntTypeFactory(MockObject $mockIntType = null)
    {
        $mockSmallIntTypeFactory = $this->getMockBuilder(SmallIntTypeFactory::class)
            ->setMethods(['newType'])
            ->getMock();
        $mockSmallIntTypeFactory->method('newType')
            ->willReturn($mockIntType);

        return $mockSmallIntTypeFactory;
    }

    protected function getExpectedIntTypeClass()
    {
        return SmallIntType::class;
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
                'smallint',
            ],
            [
                6,
                true,
                false,
                'smallint(6)',
            ],
            [
                null,
                true,
                false,
                'smallint signed',
            ],
            [
                6,
                true,
                false,
                'smallint(6) signed',
            ],
            [
                5,
                false,
                false,
                'smallint(5) unsigned',
            ],
            [
                6,
                true,
                true,
                'smallint(6) zerofill',
            ],
            [
                6,
                true,
                true,
                'smallint(6) signed zerofill',
            ],
            [
                6,
                false,
                true,
                'smallint(6) unsigned zerofill',
            ],
            [
                5,
                false,
                true,
                'SMALLINT(5) UNSIGNED ZEROFILL',
            ],
        ];
    }

    /**
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['smallint'],
            ['smallint(6)'],
            ['smallint(6) signed'],
            ['smallint(6) signed zerofill'],
            ['smallint(5) unsigned'],
            ['smallint(5) unsigned zerofill'],
            ['smallint(6) zerofill'],
            ['SMALLINT(5) UNSIGNED ZEROFILL'],
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
