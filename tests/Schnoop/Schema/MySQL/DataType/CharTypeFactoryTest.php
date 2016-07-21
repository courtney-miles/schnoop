<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/07/16
 * Time: 7:24 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\CharTypeFactory;

class CharTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @dataProvider doRecogniseProvider
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue(CharTypeFactory::doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse(CharTypeFactory::doRecognise($typeStr));
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
            '\MilesAsylum\Schnoop\Schema\MySQL\DataType\CharType',
            $collation,
            $length,
            CharTypeFactory::create($typeStr, $collation)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse(CharTypeFactory::create('varchar(254)'));
    }

    /**
     * @see testDoRecognise
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['char(123)'],
            ['CHAR(123)'],
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
            ['char']
        ];
    }

    public function createTypeProvider()
    {
        return [
            [
                'char(123)',
                'utf8_general_ci',
                123
            ],
            [
                'CHAR(123)',
                'utf8_general_ci',
                123
            ]
        ];
    }
}
