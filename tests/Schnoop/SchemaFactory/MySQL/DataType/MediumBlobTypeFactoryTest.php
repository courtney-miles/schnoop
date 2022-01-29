<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/07/16
 * Time: 9:58 PM.
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\MediumBlobTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\MediumBlobType;

class MediumBlobTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @var MediumBlobTypeFactory
     */
    protected $mediumBlobTypeFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->mediumBlobTypeFactory = new MediumBlobTypeFactory();
    }

    /**
     * @dataProvider doRecogniseProvider
     *
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue($this->mediumBlobTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     *
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse($this->mediumBlobTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider createTypeProvider
     *
     * @param $typeStr
     */
    public function testCreateType($typeStr)
    {
        $this->binaryTypeFactoryAsserts(
            MediumBlobType::class,
            $this->mediumBlobTypeFactory->createType($typeStr)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse($this->mediumBlobTypeFactory->createType('varchar(254)'));
    }

    /**
     * @see testDoRecognise
     *
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['mediumblob'],
            ['MEDIUMBLOB'],
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
            ['mediumblob'],
            ['MEDIUMBLOB'],
        ];
    }
}
