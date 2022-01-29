<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\TextTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\TextType;

class TextTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @var TextTypeFactory
     */
    protected $textTypeFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->textTypeFactory = new TextTypeFactory();
    }

    /**
     * @dataProvider doRecogniseProvider
     *
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue($this->textTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     *
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse($this->textTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider createTypeProvider
     *
     * @param $typeStr
     * @param $collation
     */
    public function testCreateType($typeStr, $collation)
    {
        $this->stringTypeFactoryAsserts(
            TextType::class,
            $collation,
            null,
            $this->textTypeFactory->createType($typeStr, $collation)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse($this->textTypeFactory->createType('varchar(254)'));
    }

    /**
     * @see testDoRecognise
     *
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['text'],
            ['TEXT'],
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
            ['tinytext'],
        ];
    }

    public function createTypeProvider()
    {
        return [
            [
                'text',
                'utf8_general_ci',
            ],
            [
                'TEXT',
                'utf8_general_ci',
            ],
        ];
    }
}
