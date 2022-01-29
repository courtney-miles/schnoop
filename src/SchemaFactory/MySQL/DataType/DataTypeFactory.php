<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use InvalidArgumentException;
use MilesAsylum\Schnoop\SchemaFactory\Exception\FactoryException;

class DataTypeFactory implements DataTypeFactoryInterface
{
    /**
     * @var DataTypeFactoryInterface[]
     */
    protected $factoryTypeHandlers = [];

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function doRecognise($typeStr)
    {
        $type = $this->extractTypeName($typeStr);

        if (false == $type) {
            throw new FactoryException("The format of the data-type string, '$typeStr', is not supported.");
        }

        return array_key_exists($type, $this->factoryTypeHandlers);
    }

    /**
     * Add a data type factory.
     *
     * @param $typeName
     * @param \MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DataTypeFactoryInterface $dataTypeFactory
     */
    public function addFactoryTypeHandler($typeName, DataTypeFactoryInterface $dataTypeFactory)
    {
        if (isset($this->factoryTypeHandlers[$typeName])) {
            trigger_error(
                "A handler had already been set for, $typeName. The handler has been replaced."
            );
        }

        $this->factoryTypeHandlers[$typeName] = $dataTypeFactory;
    }

    /**
     * Get the handler for the supplied type name.
     *
     * @param string $typeName
     *
     * @return \MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DataTypeFactoryInterface
     */
    public function getFactoryHandlerForType($typeName)
    {
        if (!isset($this->factoryTypeHandlers[$typeName])) {
            throw new InvalidArgumentException("A handler does not exist for type $typeName.");
        }

        return $this->factoryTypeHandlers[$typeName];
    }

    /**
     * Factory method for constructing this object with all known type handlers added.
     *
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
        $dataTypeFactory->addFactoryTypeHandler('mediumtext', new MediumTextTypeFactory());
        $dataTypeFactory->addFactoryTypeHandler('longtext', new LongTextTypeFactory());
        // Option type mappers.
        $dataTypeFactory->addFactoryTypeHandler('enum', new EnumTypeFactory());
        $dataTypeFactory->addFactoryTypeHandler('set', new SetTypeFactory());
        // Other type mappers.
        $dataTypeFactory->addFactoryTypeHandler('json', new JsonTypeFactory());

        return $dataTypeFactory;
    }

    /**
     * Extract the type name from the type string.
     *
     * @param string $typeStr
     *
     * @return bool|string The type name. False if the type string has an unrecognised format.
     */
    protected function extractTypeName($typeStr)
    {
        if (preg_match('/^(\w+)/', $typeStr, $matches)) {
            return strtolower($matches[1]);
        }

        return false;
    }
}
