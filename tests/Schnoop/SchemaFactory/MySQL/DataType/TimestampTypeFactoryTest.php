<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 21/07/16
 * Time: 4:30 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\TimestampTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\TimestampType;

class TimestampTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @var TimestampTypeFactory
     */
    protected $timestampTypeFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->timestampTypeFactory = new TimestampTypeFactory();
    }

    /**
     * @dataProvider doRecogniseProvider
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue($this->timestampTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse($this->timestampTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider createTypeProvider
     * @param $typeStr
     * @param $precision
     */
    public function testCreateType($typeStr, $precision)
    {
        $this->timeTypeFactoryAsserts(
            TimestampType::class,
            $precision,
            $this->timestampTypeFactory->createType($typeStr)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse($this->timestampTypeFactory->createType('binary(254)'));
    }

    /**
     * @see testDoRecognise
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['timestamp'],
            ['TIMESTAMP'],
            ['timestamp(4)'],
            ['TIMESTAMP(4)']
        ];
    }

    /**
     * @see testDoNotRecognise
     * @return array
     */
    public function doNotRecogniseProvider()
    {
        return [
            ['varchar(255)'],
            ['timestamp(foo)']
        ];
    }

    public function createTypeProvider()
    {
        return [
            [
                "timestamp",
                0
            ],
            [
                "timestamp(4)",
                4
            ]
        ];
    }
}
