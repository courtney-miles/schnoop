<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 18/07/16
 * Time: 7:04 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\TinyintTypeFactory;

class TinyintTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @dataProvider doRecogniseProvider
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue(TinyintTypeFactory::doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse(TinyintTypeFactory::doRecognise($typeStr));
    }

    /**
     * @dataProvider createTypeProvider
     * @param $typeStr
     * @param $expectedDisplayWidth
     * @param $expectedIsSigned
     */
    public function testCreateType($typeStr, $expectedDisplayWidth, $expectedIsSigned)
    {
        $this->intTypeFactoryAsserts(
            '\MilesAsylum\Schnoop\Schema\MySQL\DataType\TinyIntType',
            $expectedDisplayWidth,
            $expectedIsSigned,
            TinyintTypeFactory::create($typeStr)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse(TinyintTypeFactory::create('varchar(254)'));
    }

    /**
     * @see testDoRecognise
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['tinyint(10) unsigned'],
            ['tinyint(10)']
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
            ['tinyint']
        ];
    }

    public function createTypeProvider()
    {
        return [
            [
                'tinyint(10)',
                10,
                true
            ],
            [
                'tinyint(10) unsigned',
                10,
                false
            ],
            [
                'TINYINT(10) UNSIGNED', // Test case sensitivity.
                10,
                false
            ]
        ];
    }
}
