<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/07/16
 * Time: 8:34 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DecimalTypeFactory;

class DecimalTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @dataProvider doRecogniseProvider
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue(DecimalTypeFactory::doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse(DecimalTypeFactory::doRecognise($typeStr));
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
            '\MilesAsylum\Schnoop\Schema\MySQL\DataType\DecimalType',
            $expectedIsSigned,
            $expectedPrecision,
            $expectedScale,
            DecimalTypeFactory::create($typeStr)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse(DecimalTypeFactory::create('varchar(254)'));
    }

    /**
     * @see testDoRecognise
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['decimal(6,2) unsigned'],
            ['decimal(6,2)']
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
            ['decimal unsigned'],
            ['decimal']
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
                'decimal(6,2)',
                true,
                6,
                2
            ],
            [
                'DECIMAL(6,2) UNSIGNED', // Test case sensitivity.
                false,
                6,
                2
            ],
        ];
    }
}
