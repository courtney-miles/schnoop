<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\YearTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\YearType;

class YearTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @var YearTypeFactory
     */
    protected $yearTypeFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->yearTypeFactory = new YearTypeFactory();
    }

    /**
     * @dataProvider doRecogniseProvider
     *
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue($this->yearTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     *
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse($this->yearTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider createTypeProvider
     *
     * @param $typeStr
     */
    public function testCreateType($typeStr)
    {
        $this->assertInstanceOf(
            YearType::class,
            $this->yearTypeFactory->createType($typeStr)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse($this->yearTypeFactory->createType('binary(254)'));
    }

    /**
     * @see testDoRecognise
     *
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['year'],
            ['YEAR'],
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
            ['year(2)'],
        ];
    }

    public function createTypeProvider()
    {
        return [
            ['year'],
            ['YEAR'],
        ];
    }
}
