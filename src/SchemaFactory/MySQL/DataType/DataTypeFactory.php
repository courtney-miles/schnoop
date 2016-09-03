<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\SchemaFactory\DataTypeFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\Exception\FactoryException;
use MilesAsylum\SchnoopSchema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\TinyBlobType;

class DataTypeFactory implements DataTypeFactoryInterface
{
    /**
     * @var DataTypeFactoryInterface[]
     */
    protected $mapHandlers = [];

    public function __construct()
    {
        $this->mapHandlers = [
            'bit' => new BitTypeFactory(),
            // Integer type mappers.
            'tinyint' => new TinyIntTypeFactory(),
            'smallint' => new SmallIntTypeFactory(),
            'mediumint' => new MediumIntTypeFactory(),
            'int' => new IntTypeFactory(),
            'bigint' => new BigIntTypeFactory(),
            // Numeric-point type mappers.
            'double' => new DoubleTypeFactory(),
            'float' => new FloatTypeFactory(),
            'decimal' => new DecimalTypeFactory(),
            // Date and time mappers.
            'date' => new DateTypeFactory(),
            'time' => new TimeTypeFactory(),
            'timestamp' => new TimestampTypeFactory(),
            'datetime' => new DateTimeTypeFactory(),
            'year' => new YearTypeFactory(),
            // Char type mappers.
            'char' => new CharTypeFactory(),
            'varchar' => new VarCharTypeFactory(),
            'binary' => new BinaryTypeFactory(),
            'varbinary' => new VarBinaryTypeFactory(),
            // Blob type mappers.
            'tinyblob' => new TinyBlobType(),
            'blob' => new BlobTypeFactory(),
            'mediumblob' => new MediumBlobTypeFactory(),
            'longblob' => new LongBlobTypeFactory(),
            // Text type mappers.
            'tinytext' => new TinyTextTypeFactory(),
            'text' => new TextTypeFactory(),
            'mediumtext' => new MediumIntTypeFactory(),
            'longtext' => new LongTextTypeFactory(),
            // Option type mappers.
            'enum' => new EnumTypeFactory(),
            'set' => new SetTypeFactory()
        ];
    }

    /**
     * @param $typeStr
     * @param null $collation
     * @return DataTypeInterface|bool
     */
    public function create($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        preg_match('/^(\w+)/', $typeStr, $matches);
        $type = strtolower($matches[1]);

        return $this->mapHandlers[$type]->create($typeStr, $collation);
    }

    /**
     * @param $typeStr
     * @return bool
     * @throws FactoryException
     */
    public function doRecognise($typeStr)
    {
        if (preg_match('/^(\w+)/', $typeStr, $matches)) {
            $type = strtolower($matches[1]);

            if (array_key_exists($type, $this->mapHandlers)) {
                return true;
            } else {
                throw new FactoryException("A data-type mapper was not found for handling type $type.");
            }
        } else {
            throw new FactoryException("The format of the data-type string, $typeStr, is not supported.");
        }
    }
}
