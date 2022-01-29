<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/07/16
 * Time: 6:06 PM.
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\MediumTextTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\MediumTextType;

class MediumTextTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @var MediumTextTypeFactory
     */
    protected $mediumTextTypeFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->mediumTextTypeFactory = new MediumTextTypeFactory();
    }

    /**
     * @dataProvider doRecogniseProvider
     *
     * @param $typeStr
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue($this->mediumTextTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     *
     * @param $typeStr
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse($this->mediumTextTypeFactory->doRecognise($typeStr));
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
            MediumTextType::class,
            $collation,
            null,
            $this->mediumTextTypeFactory->createType($typeStr, $collation)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse($this->mediumTextTypeFactory->createType('varchar(254)'));
    }

    /**
     * @see testDoRecognise
     *
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['mediumtext'],
            ['MEDIUMTEXT'],
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
                'mediumtext',
                'utf8_general_ci',
            ],
            [
                'MEDIUMTEXT',
                'utf8_general_ci',
            ],
        ];
    }
}
