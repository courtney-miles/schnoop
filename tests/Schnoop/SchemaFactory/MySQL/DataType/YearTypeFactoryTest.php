<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 21/07/16
 * Time: 4:34 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\YearTypeFactory;

class YearTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @dataProvider doRecogniseProvider
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue(YearTypeFactory::doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse(YearTypeFactory::doRecognise($typeStr));
    }

    /**
     * @dataProvider createTypeProvider
     * @param $typeStr
     */
    public function testCreateType($typeStr)
    {
        $this->assertInstanceOf(
            '\MilesAsylum\Schnoop\Schema\MySQL\DataType\YearType',
            YearTypeFactory::create($typeStr)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse(YearTypeFactory::create('binary(254)'));
    }

    /**
     * @see testDoRecognise
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['year'],
            ['YEAR']
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
            ['year(2)']
        ];
    }

    public function createTypeProvider()
    {
        return [
            ['year'],
            ['YEAR']
        ];
    }
}
