<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/07/16
 * Time: 9:58 PM.
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\BlobTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\BlobType;

class BlobTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @var BlobTypeFactory
     */
    protected $blobTypeFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->blobTypeFactory = new BlobTypeFactory();
    }

    /**
     * @dataProvider doRecogniseProvider
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue($this->blobTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse($this->blobTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider createTypeProvider
     */
    public function testCreateType($typeStr)
    {
        $this->binaryTypeFactoryAsserts(
            BlobType::class,
            $this->blobTypeFactory->createType($typeStr)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse($this->blobTypeFactory->createType('varchar(254)'));
    }

    /**
     * @see testDoRecognise
     *
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['blob'],
            ['BLOB'],
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
            ['tinyblob'],
        ];
    }

    public function createTypeProvider()
    {
        return [
            ['blob'],
            ['BLOB'],
        ];
    }
}
