<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\VarCharTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\VarCharType;

class VarCharTypeFactoryTest extends SchnoopTestCase
{
    /**
     * @var VarCharTypeFactory
     */
    protected $varCharTypeFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->varCharTypeFactory = new VarCharTypeFactory();
    }

    /**
     * @dataProvider doRecogniseProvider
     */
    public function testDoRecognise($typeStr)
    {
        $this->assertTrue($this->varCharTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider doNotRecogniseProvider
     */
    public function testDoNotRecognise($typeStr)
    {
        $this->assertFalse($this->varCharTypeFactory->doRecognise($typeStr));
    }

    /**
     * @dataProvider createTypeProvider
     */
    public function testCreateType($typeStr, $collation, $length)
    {
        $this->stringTypeFactoryAsserts(
            VarCharType::class,
            $collation,
            $length,
            $this->varCharTypeFactory->createType($typeStr, $collation)
        );
    }

    public function testCreateWrongType()
    {
        $this->assertFalse($this->varCharTypeFactory->createType('binary(254)'));
    }

    /**
     * @see testDoRecognise
     *
     * @return array
     */
    public function doRecogniseProvider()
    {
        return [
            ['varchar(123)'],
            ['VARCHAR(123)'],
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
            ['binary(255)'],
            ['varchar'],
        ];
    }

    public function createTypeProvider()
    {
        return [
            [
                'varchar(123)',
                'utf8_general_ci',
                123,
            ],
            [
                'VARCHAR(123)',
                'utf8_general_ci',
                123,
            ],
        ];
    }
}
