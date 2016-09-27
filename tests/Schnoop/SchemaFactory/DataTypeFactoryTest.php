<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory;

use MilesAsylum\Schnoop\SchemaFactory\DataTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\DataTypeFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\Exception\FactoryException;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\BigIntTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\BinaryTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\BitTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\BlobTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\CharTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DateTimeTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DateTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DecimalTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DoubleTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\EnumTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\FloatTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\IntTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\LongBlobTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\LongTextTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\MediumBlobTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\MediumIntTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\MediumTextTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\SetTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\SmallIntTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\TextTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\TimestampTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\TimeTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\TinyBlobTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\TinyIntTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\TinyTextTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\VarBinaryTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\VarCharTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\YearTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\DataTypeInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class DataTypeFactoryTest extends TestCase
{
    /**
     * @var DataTypeFactory
     */
    protected $dataTypeFactory;

    /**
     * @var DataTypeFactoryInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockFactoryHandler;

    public function setUp()
    {
        parent::setUp();

        $this->mockFactoryHandler = $this->createMock(DataTypeFactoryInterface::class);

        $this->dataTypeFactory = new DataTypeFactory();
    }

    public function testDoNotRecogniseUnhandledType()
    {
        $typeStr = 'foo(123';

        $this->assertFalse($this->dataTypeFactory->doRecognise($typeStr));
    }

    public function testAddFactoryHandler()
    {
        $typeName = 'foo';
        $typeStr = 'foo(123)';

        $this->dataTypeFactory->addFactoryTypeHandler($typeName, $this->mockFactoryHandler);
        $this->assertTrue($this->dataTypeFactory->doRecognise($typeStr));
    }

    public function testGetFactoryHandlerForType()
    {
        $typeName = 'foo';
        $this->dataTypeFactory->addFactoryTypeHandler($typeName, $this->mockFactoryHandler);

        $this->assertSame(
            $this->mockFactoryHandler,
            $this->dataTypeFactory->getFactoryHandlerForType($typeName)
        );
    }

    public function testCreateType()
    {
        $typeName = 'foo';
        $typeStr = 'foo(123)';
        $collation = 'utf8_bin';

        $mockDataType = $this->createMock(DataTypeInterface::class);

        $this->mockFactoryHandler->expects($this->once())
            ->method('createType')
            ->with($typeStr, $collation)
            ->willReturn($mockDataType);

        $this->dataTypeFactory->addFactoryTypeHandler($typeName, $this->mockFactoryHandler);

        $this->assertSame($mockDataType, $this->dataTypeFactory->createType($typeStr, $collation));
    }

    /**
     * @expectedException \MilesAsylum\Schnoop\SchemaFactory\Exception\FactoryException
     * @expectedExceptionMessage A data-type mapper was not found for handling type, bogus(123).
     */
    public function testExceptionOnCreateUnknownType()
    {
        $this->dataTypeFactory->createType('bogus(123)');
    }

    /**
     * @expectedException \MilesAsylum\Schnoop\SchemaFactory\Exception\FactoryException
     * @expectedExceptionMessage The format of the data-type string, '$%^', is not supported.
     */
    public function testExceptionOnBorkedTypeString()
    {
        $this->dataTypeFactory->doRecognise('$%^');
    }

    /**
     * @expectedException \PHPUnit_Framework_Error_Notice
     * @expectedExceptionMessage A handler had already been set for, foo. The handler has been replaced.
     */
    public function testNoticeWhenAddHandlerForAlreadyHandledType()
    {
        $this->dataTypeFactory->addFactoryTypeHandler('foo', $this->mockFactoryHandler);
        $this->dataTypeFactory->addFactoryTypeHandler('foo', $this->mockFactoryHandler);
    }

    public function testAddHandlerOverwritesPreviousHandler()
    {
        $typeName = 'foo';
        $newMockFactoryHandler = $this->createMock(DataTypeFactoryInterface::class);

        $this->dataTypeFactory->addFactoryTypeHandler($typeName, $this->mockFactoryHandler);
        @$this->dataTypeFactory->addFactoryTypeHandler($typeName, $newMockFactoryHandler);

        $this->assertSame($newMockFactoryHandler, $this->dataTypeFactory->getFactoryHandlerForType($typeName));
    }

    /**
     * @dataProvider staticCreateAddHandlerTextData
     * @param $typeName
     * @param $expectedHandlerClassName
     */
    public function testStaticCreateAddHandler($typeName, $expectedHandlerClassName)
    {
        $dataTypeFactory = DataTypeFactory::createSelf();

        $this->assertInstanceOf($expectedHandlerClassName, $dataTypeFactory->getFactoryHandlerForType($typeName));
    }

    /**
     * @see testStaticCreateAddHandler
     * @return array
     */
    public function staticCreateAddHandlerTextData()
    {

        return [
            'bit' => ['bit', BitTypeFactory::class],
            // Integer type mappers.
            'tinyint' => ['tinyint', TinyIntTypeFactory::class],
            'smallint' => ['smallint', SmallIntTypeFactory::class],
            'mediumint' => ['mediumint', MediumIntTypeFactory::class],
            'int' => ['int', IntTypeFactory::class],
            'bigint' => ['bigint', BigIntTypeFactory::class],
            // Numeric-point type mappers.
            'double' => ['double', DoubleTypeFactory::class],
            'float' => ['float', FloatTypeFactory::class],
            'decimal' => ['decimal', DecimalTypeFactory::class],
            // Date and time mappers.
            'date' => ['date', DateTypeFactory::class],
            'time' => ['time', TimeTypeFactory::class],
            'timestamp' => ['timestamp', TimestampTypeFactory::class],
            'datetime' => ['datetime', DateTimeTypeFactory::class],
            'year' => ['year', YearTypeFactory::class],
            // Char type mappers.
            'char' => ['char', CharTypeFactory::class],
            'varchar' => ['varchar', VarCharTypeFactory::class],
            'binary' => ['binary', BinaryTypeFactory::class],
            'varbinary' => ['varbinary', VarBinaryTypeFactory::class],
            // Blob type mappers.
            'tinyblob' => ['tinyblob', TinyBlobTypeFactory::class],
            'blob' => ['blob', BlobTypeFactory::class],
            'mediumblob' => ['mediumblob', MediumBlobTypeFactory::class],
            'longblob' => ['longblob', LongBlobTypeFactory::class],
            // Text type mappers.
            'tinytext' => ['tinytext', TinyTextTypeFactory::class],
            'text' => ['text', TextTypeFactory::class],
            'mediumtext' => ['mediumtext', MediumTextTypeFactory::class],
            'longtext' => ['longtext', LongTextTypeFactory::class],
            // Option type mappers.
            'enum' => ['enum', EnumTypeFactory::class],
            'set' => ['set', SetTypeFactory::class],
        ];
    }
}
