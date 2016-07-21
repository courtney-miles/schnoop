<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 4/06/16
 * Time: 8:38 AM
 */

namespace MilesAsylum\Schnoop\Schema;

use MilesAsylum\Schnoop\Schema\Exception\FactoryException;
use MilesAsylum\Schnoop\Schema\MySQL\Column\Column;
use MilesAsylum\Schnoop\Schema\MySQL\Column\ColumnInterface;
use MilesAsylum\Schnoop\Schema\MySQL\Database\Database;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\BigIntType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\BinaryType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\BitType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\BlobType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\CharType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DecimalType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DoubleType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\EnumType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\FloatType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\IntType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\IntTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\LongBlobType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\LongTextType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\MediumBlobType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\MediumIntType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\MediumTextType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\NumericPointTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\NumericTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\OptionsTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\SetType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\SmallIntType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\StringTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\TextType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\TinyBlobType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\TinyIntType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\TinyTextType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\VarBinaryType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\VarCharType;
use MilesAsylum\Schnoop\Schema\MySQL\Table\Table;
use MilesAsylum\Schnoop\Schnoop;

class MySQLFactory implements FactoryInterface
{
    /**
     * @var \PDO
     */
    protected $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createDatabase(array $rawDatabase, Schnoop $schnoop)
    {
        return new Database(
            $rawDatabase['name'],
            $rawDatabase['character_set_database'],
            $rawDatabase['collation_database'],
            $schnoop
        );
    }
    
    public function createTable(array $rawTable, array $rawColumns)
    {
        $columns = [];
        
        foreach ($rawColumns as $rawCol) {
            $columns[] = $this->createColumn($rawCol);
        }
        
        $table = new Table(
            $rawTable['name'],
            $columns,
            $rawTable['engine'],
            $rawTable['row_format'],
            $rawTable['collation'],
            $rawTable['comment']
        );
        
        return $table;
    }

    /**
     * @param array $rowColumn
     * @return ColumnInterface
     */
    public function createColumn(array $rowColumn)
    {
        $dataType = $this->createDataType($rowColumn['type'], $rowColumn['collation']);
        $allowNull = strtolower($rowColumn['null']) == 'yes' ? true : false;

        $zeroFill = $autoIncrement = null;

        if ($dataType instanceof NumericTypeInterface) {
            $zeroFill = stripos($rowColumn['type'], 'zerofill') !== false;
            $autoIncrement = strtolower($rowColumn['extra']) == 'auto_increment' ? true: false;
        }

        $column = new Column(
            $rowColumn['field'],
            $dataType,
            $allowNull,
            $rowColumn['default'],
            $rowColumn['comment'],
            $zeroFill,
            $autoIncrement
        );
        
        return $column;
    }

    /**
     * @param $dataTypeString
     * @param null $collation
     * @return IntTypeInterface|NumericPointTypeInterface|OptionsTypeInterface|StringTypeInterface|null
     * @throws FactoryException
     */
    public function createDataType($dataTypeString, $collation = null)
    {
        $dataType = null;

        if (preg_match('/^(\w+)/', $dataTypeString, $matches)) {
            $namespace = 'MilesAsylum\Schnoop\Schema\MySQL\DataType\\';
            $factoryClass = $namespace . ucfirst(strtolower($matches[1])) . 'TypeFactory';

            if (class_exists($factoryClass)) {
                return $factoryClass::create($dataTypeString, $collation);
            } else {
                throw new FactoryException("A factory class was not found for the {$matches[1]} data type.");
            }
        }

        return $dataType;
    }

    /**
     * @param $optionsString
     * @return array|bool False if the string has the wrong format, otherwise the array of options.
     */
    protected function parseOptions($optionsString)
    {
        if (!preg_match("/^\('.+'\)$/", $optionsString)) {
            trigger_error(
                "Unrecognised string format for options string: $optionsString. Options string has been ignored"
            );
            return false;
        }

        $optionsString = substr_replace($optionsString, '', 0, 2);
        $optionsString = substr_replace($optionsString, '', -2, 2);

        return preg_split("/', ?'/", $optionsString);
    }
}
