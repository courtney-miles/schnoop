<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/07/16
 * Time: 7:27 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\BigintTypeFactory;

class BigintTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @dataProvider doRecogniseProvider
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue(BigintTypeFactory::doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse(BigintTypeFactory::doRecognise($typeStr));
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
            '\MilesAsylum\Schnoop\Schema\MySQL\DataType\BigIntType',
            $expectedDisplayWidth,
            $expectedIsSigned,
            BigintTypeFactory::create($typeStr)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse(BigintTypeFactory::create('varchar(254)'));
    }

    /**
     * @see testDoRecognise
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['bigint(10) unsigned'],
            ['bigint(10)']
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
            ['bigint']
        ];
    }

    public function createTypeProvider()
    {
        return [
            [
                'bigint(10)',
                10,
                true
            ],
            [
                'bigint(10) unsigned',
                10,
                false
            ],
            [
                'BIGINT(10) UNSIGNED', // Test case sensitivity.
                10,
                false
            ]
        ];
    }
}
