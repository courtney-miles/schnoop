<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\EnumTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\EnumType;

class EnumTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @var EnumTypeFactory
     */
    protected $enumTypeFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->enumTypeFactory = new EnumTypeFactory();
    }

    /**
     * @dataProvider doRecogniseProvider
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue($this->enumTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse($this->enumTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider createTypeProvider
     * @param $typeStr
     * @param $collation
     * @param $options
     */
    public function testCreateType($typeStr, $collation, $options)
    {
        $this->optionsTypeFactoryAsserts(
            EnumType::class,
            $collation,
            $options,
            $this->enumTypeFactory->createType($typeStr, $collation)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse($this->enumTypeFactory->createType('binary(254)'));
    }

    /**
     * @see testDoRecognise
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ["enum('Foo', 'Bar')"],
            ["ENUM('Foo','Bar')"],
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
            ['enum']
        ];
    }

    public function createTypeProvider()
    {
        return [
            [
                "enum('Foo','Bar')",
                'utf8_general_ci',
                array('Foo', 'Bar')
            ],
            [
                "ENUM('Foo','Bar')",
                'utf8_general_ci',
                array('Foo', 'Bar')
            ]
        ];
    }
}
