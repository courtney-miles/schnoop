<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 21/07/16
 * Time: 4:17 PM.
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DateTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\DateType;

class DateTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @var DateTypeFactory
     */
    protected $dateTypeFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->dateTypeFactory = new DateTypeFactory();
    }

    /**
     * @dataProvider doRecogniseProvider
     *
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue($this->dateTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     *
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse($this->dateTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider createTypeProvider
     *
     * @param $typeStr
     */
    public function testCreateType($typeStr)
    {
        $this->assertInstanceOf(
            DateType::class,
            $this->dateTypeFactory->createType($typeStr)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse($this->dateTypeFactory->createType('binary(254)'));
    }

    /**
     * @see testDoRecognise
     *
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['date'],
            ['DATE'],
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
            ['datetime'],
        ];
    }

    public function createTypeProvider()
    {
        return [
            ['date'],
            ['DATE'],
        ];
    }
}
