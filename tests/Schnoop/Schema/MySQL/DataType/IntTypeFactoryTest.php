<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 18/07/16
 * Time: 7:25 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\IntTypeFactory;

class IntTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @dataProvider doRecogniseProvider
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue(IntTypeFactory::doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse(IntTypeFactory::doRecognise($typeStr));
    }

    /**
     * @dataProvider createIntTypeProvider
     * @param $typeStr
     * @param $expectedDisplayWidth
     * @param $expectedIsSigned
     */
    public function testCreateIntType($typeStr, $expectedDisplayWidth, $expectedIsSigned)
    {
        $this->intTypeFactoryAsserts(
            '\MilesAsylum\Schnoop\Schema\MySQL\DataType\IntType',
            $expectedDisplayWidth,
            $expectedIsSigned,
            IntTypeFactory::create($typeStr)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse(IntTypeFactory::create('varchar(254)'));
    }

    /**
     * @see testDoRecognise
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['int(10) unsigned'],
            ['int(10)'],
            ['integer(10) unsigned'],
            ['integer(10)']
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
            ['int'],
            ['integer']
        ];
    }

    public function createIntTypeProvider()
    {
        return [
            [
                'int(10)',
                10,
                true
            ],
            [
                'INT(10) UNSIGNED', // Test case sensitivity.
                10,
                false
            ],
            [
                'integer(10) unsigned',
                10,
                false
            ]
        ];
    }
}
