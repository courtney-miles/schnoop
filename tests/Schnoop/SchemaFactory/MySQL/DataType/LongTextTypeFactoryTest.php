<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/07/16
 * Time: 6:14 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\LongTextTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\LongTextType;

class LongTextTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @var LongTextTypeFactory
     */
    protected $longTextTypeFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->longTextTypeFactory = new LongTextTypeFactory();
    }

    /**
     * @dataProvider doRecogniseProvider
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue($this->longTextTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse($this->longTextTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider createTypeProvider
     * @param $typeStr
     * @param $collation
     */
    public function testCreateType($typeStr, $collation)
    {
        $this->stringTypeFactoryAsserts(
            LongTextType::class,
            $collation,
            null,
            $this->longTextTypeFactory->createType($typeStr, $collation)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse($this->longTextTypeFactory->createType('varchar(254)'));
    }

    /**
     * @see testDoRecognise
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['longtext'],
            ['LONGTEXT'],
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
            ['text']
        ];
    }

    public function createTypeProvider()
    {
        return [
            [
                'longtext',
                'utf8_general_ci'
            ],
            [
                'LONGTEXT',
                'utf8_general_ci'
            ]
        ];
    }
}
