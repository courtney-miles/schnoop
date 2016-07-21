<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/07/16
 * Time: 7:23 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\MediumintTypeFactory;

class MediumintTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @dataProvider doRecogniseProvider
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue(MediumintTypeFactory::doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse(MediumintTypeFactory::doRecognise($typeStr));
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
            '\MilesAsylum\Schnoop\Schema\MySQL\DataType\MediumIntType',
            $expectedDisplayWidth,
            $expectedIsSigned,
            MediumintTypeFactory::create($typeStr)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse(MediumintTypeFactory::create('varchar(254)'));
    }

    /**
     * @see testDoRecognise
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['mediumint(10) unsigned'],
            ['mediumint(10)']
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
            ['mediumint']
        ];
    }

    public function createTypeProvider()
    {
        return [
            [
                'mediumint(10)',
                10,
                true
            ],
            [
                'mediumint(10) unsigned',
                10,
                false
            ],
            [
                'MEDIUMINT(10) UNSIGNED', // Test case sensitivity.
                10,
                false
            ]
        ];
    }
}
