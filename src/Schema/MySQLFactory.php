<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 4/06/16
 * Time: 8:38 AM
 */

namespace MilesAsylum\Schnoop\Schema;

use MilesAsylum\Schnoop\Schema\MySQL\Column\Column;
use MilesAsylum\Schnoop\Schema\MySQL\Column\ColumnInterface;
use MilesAsylum\Schnoop\Schema\MySQL\Column\NumericColumn;
use MilesAsylum\Schnoop\Schema\MySQL\Column\NumericColumnInterface;
use MilesAsylum\Schnoop\Schema\MySQL\Database\Database;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\BigIntType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\BinaryType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\BitType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\BlobType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\CharType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DecimalType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DoubleType;
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

    /**
     * @var \PDOStatement
     */
    protected $stmtSelCharSetFromCollation;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->stmtSelCharSetFromCollation = $this->pdo->prepare(<<< SQL
SHOW COLLATION WHERE Collation = ?
SQL
        );
    }

    public function newDatabase(array $rawDatabase, Schnoop $schnoop)
    {
        return new Database(
            $rawDatabase['name'],
            $rawDatabase['character_set_database'],
            $rawDatabase['collation_database'],
            $schnoop
        );
    }
    
    public function newTable(array $rawTable, array $rawColumns)
    {
        $columns = [];
        
        foreach ($rawColumns as $rawCol) {
            $columns[] = $this->newColumn($rawCol);
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
     * @return ColumnInterface|NumericColumnInterface
     */
    public function newColumn(array $rowColumn)
    {
        $dataType = $this->newDataType($rowColumn['type'], $rowColumn['collation']);
        $autoIncrement = strtolower($rowColumn['extra']) == 'auto_increment' ? true: false;
        $allowNull = strtolower($rowColumn['null']) == 'yes' ? true : false;

        if ($dataType instanceof NumericTypeInterface) {
            $column = new NumericColumn(
                $rowColumn['field'],
                $dataType,
                stripos($rowColumn['type'], 'zerofill') !== false,
                $allowNull,
                $rowColumn['default'],
                $autoIncrement,
                $rowColumn['comment']
            );
        } else {
            $column = new Column(
                $rowColumn['field'],
                $dataType,
                $allowNull,
                $rowColumn['default'],
                $rowColumn['comment']
            );
        }
        
        return $column;
    }

    /**
     * @param $dataTypeString
     * @param null $collation
     * @return IntTypeInterface|NumericPointTypeInterface|StringTypeInterface|null
     */
    public function newDataType($dataTypeString, $collation = null)
    {
        $dataType = null;
        $dataTypeString = strtolower($dataTypeString);

        if (preg_match('/^(tiny|small|medium|big)?int(eger)?\((\d+)\)( unsigned)?/', $dataTypeString, $matches)) {
            $displayWidth = $matches[3];
            $signed = empty($matches[4]);

            switch ($matches[1]) {
                case 'tiny':
                    $dataType = new TinyIntType($displayWidth, $signed);
                    break;
                case 'small':
                    $dataType = new SmallIntType($displayWidth, $signed);
                    break;
                case 'medium':
                    $dataType = new MediumIntType($displayWidth, $signed);
                    break;
                case 'big':
                    $dataType = new BigIntType($displayWidth, $signed);
                    break;
                default:
                    $dataType = new IntType($displayWidth, $signed);
                    break;
            }
        } elseif (preg_match('/^(decimal|float|double)\((\d+),(\d+)\)( unsigned)?/', $dataTypeString, $matches)) {
            $precision = $matches[2];
            $scale = $matches[3];
            $signed = empty($matches[4]);

            switch ($matches[1]) {
                case 'decimal':
                    $dataType = new DecimalType($precision, $scale, $signed);
                    break;
                case 'double':
                    $dataType = new DoubleType($precision, $scale, $signed);
                    break;
                case 'float':
                    $dataType = new FloatType($precision, $scale, $signed);
            }
        } elseif (preg_match('/^(var)?binary\((\d+)\)$/', $dataTypeString, $matches)) {
            $isVarBinary = !empty($matches[1]);
            $length = $matches[2];

            if ($isVarBinary) {
                $dataType = new VarBinaryType($length);
            } else {
                $dataType = new BinaryType($length);
            }
        } elseif (preg_match('/^bit\((\d+)\)$/', $dataTypeString, $matches)) {
            $dataType = new BitType($matches[1]);
        } else {
            $characterSet = !empty($collation) ? $this->getCharacterSet($collation) : null;

            if (preg_match('/^(var)?char\((\d+)\)$/', $dataTypeString, $matches)) {
                $isVarChar = !empty($matches[1]);
                $length = $matches[2];

                if ($isVarChar) {
                    $dataType = new VarCharType($length, $characterSet, $collation);
                } else {
                    $dataType = new CharType($length, $characterSet, $collation);
                }
            } else {
                switch ($dataTypeString) {
                    case 'text':
                        $dataType = new TextType($characterSet, $collation);
                        break;
                    case 'tinytext':
                        $dataType = new TinyTextType($characterSet, $collation);
                        break;
                    case 'mediumtext':
                        $dataType = new MediumTextType($characterSet, $collation);
                        break;
                    case 'longtext':
                        $dataType = new LongTextType($characterSet, $collation);
                        break;
                    case 'blob':
                        $dataType = new BlobType();
                        break;
                    case 'tinyblob':
                        $dataType = new TinyBlobType();
                        break;
                    case 'mediumblob':
                        $dataType = new MediumBlobType();
                        break;
                    case 'longblob':
                        $dataType = new LongBlobType();
                        break;
                }
            }
        }

        return $dataType;
    }

    public function getCharacterSet($collation)
    {
        $this->stmtSelCharSetFromCollation->execute(array($collation));

        return $this->stmtSelCharSetFromCollation->fetch(\PDO::FETCH_ASSOC)['Charset'];
    }
}