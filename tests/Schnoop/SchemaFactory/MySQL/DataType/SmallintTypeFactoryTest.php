<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/07/16
 * Time: 7:16 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\SmallintTypeFactory;

class SmallintTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @dataProvider doRecogniseProvider
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue(SmallintTypeFactory::doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse(SmallintTypeFactory::doRecognise($typeStr));
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
            '\MilesAsylum\Schnoop\Schema\MySQL\DataType\SmallIntType',
            $expectedDisplayWidth,
            $expectedIsSigned,
            SmallintTypeFactory::create($typeStr)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse(SmallintTypeFactory::create('varchar(254)'));
    }

    /**
     * @see testDoRecognise
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['smallint(10) unsigned'],
            ['smallint(10)']
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
        ];
    }

    public function createTypeProvider()
    {
        return [
            [
                'smallint(10)',
                10,
                true
            ],
            [
                'smallint(10) unsigned',
                10,
                false
            ],
            [
                'SMALLINT(10) UNSIGNED', // Test case sensitivity.
                10,
                false
            ]
        ];
    }
}
