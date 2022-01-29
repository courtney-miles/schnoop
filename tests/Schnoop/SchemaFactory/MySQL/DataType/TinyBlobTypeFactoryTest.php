<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\TinyBlobTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\TinyBlobType;

class TinyBlobTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @var TinyBlobTypeFactory
     */
    protected $tinyBlobTypeFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->tinyBlobTypeFactory = new TinyBlobTypeFactory();
    }

    /**
     * @dataProvider doRecogniseProvider
     *
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue($this->tinyBlobTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     *
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse($this->tinyBlobTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider createTypeProvider
     *
     * @param $typeStr
     */
    public function testCreateType($typeStr)
    {
        $this->binaryTypeFactoryAsserts(
            TinyBlobType::class,
            $this->tinyBlobTypeFactory->createType($typeStr)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse($this->tinyBlobTypeFactory->createType('varchar(254)'));
    }

    /**
     * @see testDoRecognise
     *
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['tinyblob'],
            ['TINYBLOB'],
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
            ['blob'],
        ];
    }

    public function createTypeProvider()
    {
        return [
            ['tinyblob'],
            ['TINYBLOB'],
        ];
    }
}
