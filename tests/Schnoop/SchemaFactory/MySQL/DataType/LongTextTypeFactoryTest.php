<?php

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

    protected function setUp(): void
    {
        parent::setUp();

        $this->longTextTypeFactory = new LongTextTypeFactory();
    }

    /**
     * @dataProvider doRecogniseProvider
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue($this->longTextTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse($this->longTextTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider createTypeProvider
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
     *
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
                'longtext',
                'utf8_general_ci',
            ],
            [
                'LONGTEXT',
                'utf8_general_ci',
            ],
        ];
    }
}
