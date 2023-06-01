<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\TinyTextTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\TinyTextType;

class TinyTextTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @var TinyTextTypeFactory
     */
    protected $tinyTextTypeFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tinyTextTypeFactory = new TinyTextTypeFactory();
    }

    /**
     * @dataProvider doRecogniseProvider
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue($this->tinyTextTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse($this->tinyTextTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider createTypeProvider
     */
    public function testCreateType($typeStr, $collation)
    {
        $this->stringTypeFactoryAsserts(
            TinyTextType::class,
            $collation,
            null,
            $this->tinyTextTypeFactory->createType($typeStr, $collation)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse($this->tinyTextTypeFactory->createType('varchar(254)'));
    }

    /**
     * @see testDoRecognise
     *
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['tinytext'],
            ['TINYTEXT'],
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
            ['text'],
        ];
    }

    public function createTypeProvider()
    {
        return [
            [
                'tinytext',
                'utf8_general_ci',
            ],
            [
                'TINYTEXT',
                'utf8_general_ci',
            ],
        ];
    }
}
