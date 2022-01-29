<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 21/07/16
 * Time: 7:05 AM.
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\SetTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\SetType;

class SetTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @var SetTypeFactory
     */
    protected $setTypeFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->setTypeFactory = new SetTypeFactory();
    }

    /**
     * @dataProvider doRecogniseProvider
     *
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue($this->setTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     *
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse($this->setTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider createTypeProvider
     *
     * @param $typeStr
     * @param $collation
     * @param $options
     */
    public function testCreateType($typeStr, $collation, $options)
    {
        $this->optionsTypeFactoryAsserts(
            SetType::class,
            $collation,
            $options,
            $this->setTypeFactory->createType($typeStr, $collation)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse($this->setTypeFactory->createType('binary(254)'));
    }

    /**
     * @see testDoRecognise
     *
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
     *
     * @return array
     */
    public function doNotRecogniseProvider()
    {
        return [
            ['varchar(255)'],
            ['set'],
        ];
    }

    public function createTypeProvider()
    {
        return [
            [
                "set('Foo','Bar')",
                'utf8_general_ci',
                ['Foo', 'Bar'],
            ],
            [
                "SET('Foo','Bar')",
                'utf8_general_ci',
                ['Foo', 'Bar'],
            ],
        ];
    }
}
