<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 21/07/16
 * Time: 7:20 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\EnumTypeFactory;

class EnumTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @dataProvider doRecogniseProvider
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue(EnumTypeFactory::doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse(EnumTypeFactory::doRecognise($typeStr));
    }

    /**
     * @dataProvider createTypeProvider
     * @param $typeStr
     * @param $collation
     * @param $options
     */
    public function testCreateType($typeStr, $collation, $options)
    {
        $this->optionsTypeFactoryAsserts(
            '\MilesAsylum\Schnoop\Schema\MySQL\DataType\EnumType',
            $collation,
            $options,
            EnumTypeFactory::create($typeStr, $collation)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse(EnumTypeFactory::create('binary(254)'));
    }

    /**
     * @see testDoRecognise
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ["enum('Foo', 'Bar')"],
            ["ENUM('Foo','Bar')"],
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
            ['enum']
        ];
    }

    public function createTypeProvider()
    {
        return [
            [
                "enum('Foo','Bar')",
                'utf8_general_ci',
                array('Foo', 'Bar')
            ],
            [
                "ENUM('Foo','Bar')",
                'utf8_general_ci',
                array('Foo', 'Bar')
            ]
        ];
    }
}
