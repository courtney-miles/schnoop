<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 21/07/16
 * Time: 7:05 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\SetTypeFactory;

class SetTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @dataProvider doRecogniseProvider
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue(SetTypeFactory::doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse(SetTypeFactory::doRecognise($typeStr));
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
            '\MilesAsylum\Schnoop\Schema\MySQL\DataType\SetType',
            $collation,
            $options,
            SetTypeFactory::create($typeStr, $collation)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse(SetTypeFactory::create('binary(254)'));
    }

    /**
     * @see testDoRecognise
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ["set('Foo', 'Bar')"],
            ["SET('Foo','Bar')"],
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
            ['set']
        ];
    }

    public function createTypeProvider()
    {
        return [
            [
                "set('Foo','Bar')",
                'utf8_general_ci',
                array('Foo', 'Bar')
            ],
            [
                "SET('Foo','Bar')",
                'utf8_general_ci',
                array('Foo', 'Bar')
            ]
        ];
    }
}
