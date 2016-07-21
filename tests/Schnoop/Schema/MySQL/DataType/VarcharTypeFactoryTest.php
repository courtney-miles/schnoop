<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/07/16
 * Time: 7:32 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\VarcharTypeFactory;

class VarcharTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @dataProvider doRecogniseProvider
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue(VarcharTypeFactory::doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse(VarcharTypeFactory::doRecognise($typeStr));
    }

    /**
     * @dataProvider createTypeProvider
     * @param $typeStr
     * @param $collation
     * @param $length
     */
    public function testCreateType($typeStr, $collation, $length)
    {
        $this->stringTypeFactoryAsserts(
            '\MilesAsylum\Schnoop\Schema\MySQL\DataType\VarCharType',
            $collation,
            $length,
            VarcharTypeFactory::create($typeStr, $collation)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse(VarcharTypeFactory::create('binary(254)'));
    }

    /**
     * @see testDoRecognise
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['varchar(123)'],
            ['VARCHAR(123)'],
        ];
    }

    /**
     * @see testDoNotRecognise
     * @return array
     */
    public function doNotRecogniseProvider()
    {
        return [
            ['binary(255)'],
            ['varchar']
        ];
    }

    public function createTypeProvider()
    {
        return [
            [
                'varchar(123)',
                'utf8_general_ci',
                123
            ],
            [
                'VARCHAR(123)',
                'utf8_general_ci',
                123
            ]
        ];
    }
}
