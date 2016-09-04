<?php

namespace MilesAsylum\Schnoop\SchemaFactory;

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

class DataTypeFactory implements DataTypeFactoryInterface
{
    /**
     * @var DataTypeFactoryInterface[]
     */
    protected $factoryTypeHandlers = [];

    /**
     * @param $typeStr
     * @param null $collation
     * @return bool|DataTypeInterface
     * @throws FactoryException
     */
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            throw new FactoryException("A data-type mapper was not found for handling type, $typeStr.");
        }

        $type = $this->extractTypeName($typeStr);

        return $this->factoryTypeHandlers[$type]->createType($typeStr, $collation);
    }

    /**
     * @param $typeStr
     * @return bool
     * @throws FactoryException
     */
    public function doRecognise($typeStr)
    {
        $type = $this->extractTypeName($typeStr);

        if ($type == false) {
            throw new FactoryException("The format of the data-type string, '$typeStr', is not supported.");
        }

        return array_key_exists($type, $this->factoryTypeHandlers);
    }

    public function addFactoryTypeHandler($typeName, DataTypeFactoryInterface $dataTypeFactory)
    {
        if (isset($this->factoryTypeHandlers[$typeName])) {
            trigger_error(
                "A handler had already been set for, $typeName. The handler has been replaced."
            );
        }

        $this->factoryTypeHandlers[$typeName] = $dataTypeFactory;
    }

    public function getFactoryHandlerForType($typeName)
    {
        return $this->factoryTypeHandlers[$typeName];
    }

    /**
     * @return DataTypeFactory
     */
    public static function createSelf()
    {
        $dataTypeFactory = new self();

        $dataTypeFactory->addFactoryTypeHandler('bit', new BitTypeFactory());
        // Integer type mappers.
        $dataTypeFactory->addFactoryTypeHandler('tinyint', new TinyIntTypeFactory());
        $dataTypeFactory->addFactoryTypeHandler('smallint', new SmallIntTypeFactory());
        $dataTypeFactory->addFactoryTypeHandler('mediumint', new MediumIntTypeFactory());
        $dataTypeFactory->addFactoryTypeHandler('int', new IntTypeFactory());
        $dataTypeFactory->addFactoryTypeHandler('bigint', new BigIntTypeFactory());
        // Numeric-point type mappers.
        $dataTypeFactory->addFactoryTypeHandler('double', new DoubleTypeFactory());
        $dataTypeFactory->addFactoryTypeHandler('float', new FloatTypeFactory());
        $dataTypeFactory->addFactoryTypeHandler('decimal', new DecimalTypeFactory());
        // Date and time mappers.
        $dataTypeFactory->addFactoryTypeHandler('date', new DateTypeFactory());
        $dataTypeFactory->addFactoryTypeHandler('time', new TimeTypeFactory());
        $dataTypeFactory->addFactoryTypeHandler('timestamp', new TimestampTypeFactory());
        $dataTypeFactory->addFactoryTypeHandler('datetime', new DateTimeTypeFactory());
        $dataTypeFactory->addFactoryTypeHandler('year', new YearTypeFactory());
        // Char type mappers.
        $dataTypeFactory->addFactoryTypeHandler('char', new CharTypeFactory());
        $dataTypeFactory->addFactoryTypeHandler('varchar', new VarCharTypeFactory());
        $dataTypeFactory->addFactoryTypeHandler('binary', new BinaryTypeFactory());
        $dataTypeFactory->addFactoryTypeHandler('varbinary', new VarBinaryTypeFactory());
        // Blob type mappers.
        $dataTypeFactory->addFactoryTypeHandler('tinyblob', new TinyBlobTypeFactory());
        $dataTypeFactory->addFactoryTypeHandler('blob', new BlobTypeFactory());
        $dataTypeFactory->addFactoryTypeHandler('mediumblob', new MediumBlobTypeFactory());
        $dataTypeFactory->addFactoryTypeHandler('longblob', new LongBlobTypeFactory());
        // Text type mappers.
        $dataTypeFactory->addFactoryTypeHandler('tinytext', new TinyTextTypeFactory());
        $dataTypeFactory->addFactoryTypeHandler('text', new TextTypeFactory());
        $dataTypeFactory->addFactoryTypeHandler('mediumtext', new MediumIntTypeFactory());
        $dataTypeFactory->addFactoryTypeHandler('longtext', new LongTextTypeFactory());
        // Option type mappers.
        $dataTypeFactory->addFactoryTypeHandler('enum', new EnumTypeFactory());
        $dataTypeFactory->addFactoryTypeHandler('set', new SetTypeFactory());

        return $dataTypeFactory;
    }

    protected function extractTypeName($typeStr)
    {
        if (preg_match('/^(\w+)/', $typeStr, $matches)) {
            return strtolower($matches[1]);
        }

        return false;
    }
}
