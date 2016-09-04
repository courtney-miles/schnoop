<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\LongBlobTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\LongBlobType;

class LongBlobTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @var LongBlobTypeFactory
     */
    protected $longBlobTypeFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->longBlobTypeFactory = new LongBlobTypeFactory();
    }

    /**
     * @dataProvider doRecogniseProvider
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue($this->longBlobTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse($this->longBlobTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider createTypeProvider
     * @param $typeStr
     */
    public function testCreateType($typeStr)
    {
        $this->binaryTypeFactoryAsserts(
            LongBlobType::class,
            $this->longBlobTypeFactory->createType($typeStr)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse($this->longBlobTypeFactory->createType('varchar(254)'));
    }

    /**
     * @see testDoRecognise
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['longblob'],
            ['LONGBLOB'],
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
            ['blob']
        ];
    }

    public function createTypeProvider()
    {
        return [
            ['longblob'],
            ['LONGBLOB']
        ];
    }
}
