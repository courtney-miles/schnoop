<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/07/16
 * Time: 6:50 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\FloatTypeFactory;

class FloatTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @dataProvider doRecogniseProvider
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue(FloatTypeFactory::doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse(FloatTypeFactory::doRecognise($typeStr));
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
            '\MilesAsylum\Schnoop\Schema\MySQL\DataType\FloatType',
            $expectedIsSigned,
            $expectedPrecision,
            $expectedScale,
            FloatTypeFactory::create($typeStr)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse(FloatTypeFactory::create('varchar(254)'));
    }

    /**
     * @see testDoRecognise
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['float(6,2) unsigned'],
            ['float(6,2)'],
            ['float unsigned'],
            ['float']
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
                'float(6,2)',
                true,
                6,
                2
            ],
            [
                'FLOAT(6,2) UNSIGNED', // Test case sensitivity.
                false,
                6,
                2
            ],
            [
                'float',
                true,
                null,
                null
            ],
            [
                'float unsigned',
                false,
                null,
                null
            ]
        ];
    }
}
