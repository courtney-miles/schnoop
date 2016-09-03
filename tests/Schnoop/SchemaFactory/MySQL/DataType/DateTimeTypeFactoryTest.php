<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 21/07/16
 * Time: 7:36 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DateTimeTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\DateTimeType;

class DateTimeTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @var DateTimeTypeFactory
     */
    protected $dateTimeTypeFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->dateTimeTypeFactory = new DateTimeTypeFactory();
    }

    /**
     * @dataProvider doRecogniseProvider
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue($this->dateTimeTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse($this->dateTimeTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider createTypeProvider
     * @param $typeStr
     * @param $precision
     */
    public function testCreateType($typeStr, $precision)
    {
        $this->timeTypeFactoryAsserts(
            DateTimeType::class,
            $precision,
            $this->dateTimeTypeFactory->create($typeStr)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse($this->dateTimeTypeFactory->create('binary(254)'));
    }

    /**
     * @see testDoRecognise
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['datetime'],
            ['DATETIME'],
            ['datetime(4)'],
            ['DATETIME(4)']
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
            ['datetime(foo)']
        ];
    }

    public function createTypeProvider()
    {
        return [
            [
                "datetime",
                0
            ],
            [
                "datetime(4)",
                4
            ]
        ];
    }
}
