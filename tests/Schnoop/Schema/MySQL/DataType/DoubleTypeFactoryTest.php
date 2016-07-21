<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/07/16
 * Time: 8:16 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DoubleTypeFactory;

class DoubleTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @dataProvider doRecogniseProvider
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue(DoubleTypeFactory::doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse(DoubleTypeFactory::doRecognise($typeStr));
    }

    /**
     * @dataProvider createTypeProvider
     * @param $typeStr
     * @param $expectedIsSigned
     * @param $expectedPrecision
     * @param $expectedScale
     */
    public function testCreateType($typeStr, $expectedIsSigned, $expectedPrecision, $expectedScale)
    {
        $this->numericPointTypeFactoryAsserts(
            '\MilesAsylum\Schnoop\Schema\MySQL\DataType\DoubleType',
            $expectedIsSigned,
            $expectedPrecision,
            $expectedScale,
            DoubleTypeFactory::create($typeStr)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse(DoubleTypeFactory::create('varchar(254)'));
    }

    /**
     * @see testDoRecognise
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['double(6,2) unsigned'],
            ['double(6,2)'],
            ['double unsigned'],
            ['double']
        ];
    }

    /**
     * @see testDoNotRecognise
     * @return array
     */
    public function doNotRecogniseProvider()
    {
        return [
            ['varchar(255)']
        ];
    }

    /**
     * @see testCreateType
     * @return array
     */
    public function createTypeProvider()
    {
        return [
            [
                'double(6,2)',
                true,
                6,
                2
            ],
            [
                'DOUBLE(6,2) UNSIGNED', // Test case sensitivity.
                false,
                6,
                2
            ],
            [
                'double',
                true,
                null,
                null
            ],
            [
                'double unsigned',
                false,
                null,
                null
            ]
        ];
    }
}
