<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 8/06/16
 * Time: 7:57 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema;


use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQLFactory;
use MilesAsylum\Schnoop\Schnoop;
use PHPUnit_Framework_MockObject_MockObject;

class MySQLFactoryTest extends SchnoopTestCase
{
    /**
     * @var MySQLFactory
     */
    protected $mysqlFactory;

    /**
     * @var \PDO|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockPdo;

    /**
     * @var \PDOStatement|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockPdoStmt;
    
    public function setUp()
    {
        parent::setUp();

        $this->mockPdo = $this->createMock('MilesAsylum\Schnoop\PHPUnit\Schnoop\MockPdo');
        $this->mockPdoStmt = $this->createMock('\PDOStatement');

        $this->mockPdo->method('prepare')
            ->willReturn($this->mockPdoStmt);

        $this->mysqlFactory = new MySQLFactory($this->mockPdo);
    }
    
    public function testNewDatabase()
    {
        /** @var Schnoop|PHPUnit_Framework_MockObject_MockObject $mockSchnoop */
        $mockSchnoop = $this->createMock('MilesAsylum\Schnoop\Schnoop');
        $mockSchnoop->method('getTableList')
            ->willReturn([]);

        $rawDatabaseData = [
            'name' => 'schnoop',
            'character_set_database' => 'utf8mb4',
            'collation_database' => 'utf8mb4_unicode_ci'
        ];

        $this->assertInstanceOf(
            'MilesAsylum\Schnoop\Schema\MySQL\Database\Database',
            $this->mysqlFactory->newDatabase($rawDatabaseData, $mockSchnoop)
        );
    }

    /**
     * @dataProvider intTypeProvider
     * @param string $integerTypeStr
     * @param string $expectedInstanceType
     * @param int $expectedDisplayWidth
     * @param bool $expectedSign
     */
    public function testNewIntType($integerTypeStr, $expectedInstanceType, $expectedDisplayWidth, $expectedSign)
    {
        $dataType = $this->mysqlFactory->newDataType($integerTypeStr);
        
        $this->assertInstanceOf($expectedInstanceType, $dataType);
        $this->assertSame($expectedDisplayWidth, $dataType->getDisplayWidth());
        $this->assertSame($expectedSign, $dataType->isSigned());
    }

    /**
     * @dataProvider numericPointTypeProvider
     * @param string $numericTypeStr
     * @param string $expectedInstanceType
     * @param int $expectedPrecision
     * @param int $expectedScale
     * @param bool $expectedSign
     */
    public function testNewNumericPointType(
        $numericTypeStr,
        $expectedInstanceType,
        $expectedPrecision,
        $expectedScale,
        $expectedSign
    ) {
        $dataType = $this->mysqlFactory->newDataType($numericTypeStr);

        $this->assertInstanceOf($expectedInstanceType, $dataType);
        $this->assertSame($expectedPrecision, $dataType->getPrecision());
        $this->assertSame($expectedScale, $dataType->getScale());
        $this->assertSame($expectedSign, $dataType->isSigned());
    }

    /**
     * @dataProvider stringTypeProvider
     * @param $stringTypeStr
     * @param $collation
     * @param $expectedInstanceType
     * @param $expectedCharacterSet
     * @param null $expectedLength
     */
    public function testNewStringType(
        $stringTypeStr,
        $collation,
        $expectedInstanceType,
        $expectedCharacterSet,
        $expectedLength = null
    ) {
        $this->mockPdoStmt->expects($this->atLeastOnce())
            ->method('execute')
            ->with([$collation])
            ->willReturn($this->returnSelf());

        $this->mockPdoStmt->expects($this->atLeastOnce())
            ->method('fetch')
            ->willReturn(['Charset' => 'utf8']);

        $dataType = $this->mysqlFactory->newDataType($stringTypeStr, $collation);

        $this->assertInstanceOf($expectedInstanceType, $dataType);

        if (isset($expectedLength)) {
            $this->assertSame($expectedLength, $dataType->getLength());
        }

        $this->assertSame($expectedCharacterSet, $dataType->getCharacterSet());
        $this->assertSame($collation, $dataType->getCollation());
    }

    /**
     * @dataProvider binaryTypeProvider
     * @param $binaryTypeStr
     * @param $expectedInstanceType
     * @param null $expectedLength
     */
    public function testNewBinaryType($binaryTypeStr, $expectedInstanceType, $expectedLength = null)
    {
        $dataType = $this->mysqlFactory->newDataType($binaryTypeStr);
        
        $this->assertInstanceOf($expectedInstanceType, $dataType);

        if (isset($expectedLength)) {
            $this->assertSame($expectedLength, $dataType->getLength());
        }
    }

    /**
     * @dataProvider columnTypeProvider
     * @param $columnArr
     * @param $expectedInstance
     */
    public function testCorrectColumnInstanceForDataType($columnArr, $expectedInstance)
    {
        $column = $this->mysqlFactory->newColumn($columnArr);

        $this->assertInstanceOf(
            $expectedInstance,
            $column
        );
    }

    public function testNewColumn()
    {
        $column = $this->mysqlFactory->newColumn(
            [
                'field' => 'schnoop_char',
                'type' => 'CHAR(3)',
                'collation' => 'utf8_general_ci',
                'null' => 'YES',
                'default' => '123',
                'extra' => null,
                'comment' => 'Schnoop comment'
            ]
        );

        $this->assertSame('schnoop_char', $column->getName());
        $this->assertSame(true, $column->isAllowNull());
        $this->assertSame('123', $column->getDefault());
        $this->assertSame('Schnoop comment', $column->getComment());
    }

    public function testNewNumericColumn()
    {
        $column = $this->mysqlFactory->newColumn(
            [
                'field' => 'schnoop_int',
                'type' => 'INT(3) UNSIGNED ZEROFILL',
                'collation' => null,
                'null' => 'YES',
                'default' => '123',
                'extra' => 'auto_increment',
                'comment' => 'Schnoop comment'
            ]
        );

        $this->assertSame('schnoop_int', $column->getName());
        $this->assertSame(true, $column->isZeroFill());
        $this->assertSame(true, $column->isAllowNull());
        $this->assertSame(123, $column->getDefault());
        $this->assertSame(true, $column->isAutoIncrement());
        $this->assertSame('Schnoop comment', $column->getComment());
    }

    public function testNewTable()
    {
        $name = 'schnoop_tbl';
        $engine = 'InnoDB';
        $rowFormat = 'compact';
        $defaultCollation = 'utf8_general_ci';
        $comment = 'Schnoop comment';
        $columnName = 'schnoop_int';

        $table = $this->mysqlFactory->newTable(
            [
                'name' => $name,
                'engine' => $engine,
                'row_format' => $rowFormat,
                'collation' => $defaultCollation,
                'comment' => $comment
            ],
            [
                [
                    'field' => $columnName,
                    'type' => 'INT(3)',
                    'collation' => null,
                    'null' => 'YES',
                    'default' => '123',
                    'extra' => 'auto_increment',
                    'comment' => 'Schnoop comment'
                ]
            ]
        );

        $this->assertSame($name, $table->getName());
        $this->assertSame($engine, $table->getEngine());
        $this->assertSame($rowFormat, $table->getRowFormat());
        $this->assertSame($defaultCollation, $table->getDefaultCollation());
        $this->assertSame($comment, $table->getComment());

        $this->assertInstanceOf(
            'MilesAsylum\Schnoop\Schema\MySQL\Column\ColumnInterface',
            $table->getColumn($columnName)
        );
    }

    /**
     * @see testNewIntType
     * @return array
     */
    public function intTypeProvider()
    {
        return [
            [
                'INT(3)',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\IntType',
                3,
                true
            ],
            [
                'INT(3) SIGNED',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\IntType',
                3,
                true
            ],
            [
                'INT(3) UNSIGNED',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\IntType',
                3,
                false
            ],
            [
                'TINYINT(3)',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\TinyIntType',
                3,
                true
            ],
            [
                'TINYINT(3) SIGNED',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\TinyIntType',
                3,
                true
            ],
            [
                'TINYINT(3) UNSIGNED',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\TinyIntType',
                3,
                false
            ],
            [
                'SMALLINT(3)',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\SmallIntType',
                3,
                true
            ],
            [
                'SMALLINT(3) SIGNED',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\SmallIntType',
                3,
                true
            ],
            [
                'SMALLINT(3) UNSIGNED',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\SmallIntType',
                3,
                false
            ],
            [
                'MEDIUMINT(3)',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\MediumIntType',
                3,
                true
            ],
            [
                'MEDIUMINT(3) SIGNED',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\MediumIntType',
                3,
                true
            ],
            [
                'MEDIUMINT(3) UNSIGNED',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\MediumIntType',
                3,
                false
            ],
            [
                'BIGINT(3)',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\BigIntType',
                3,
                true
            ],
            [
                'BIGINT(3) SIGNED',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\BigIntType',
                3,
                true
            ],
            [
                'BIGINT(3) UNSIGNED',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\BigIntType',
                3,
                false
            ]
        ];
    }

    /**
     * @see testNewNumericPointType
     * @return array
     */
    public function numericPointTypeProvider()
    {
        return [
            [
                'DECIMAL(6,2)',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\DecimalType',
                6,
                2,
                true
            ],
            [
                'DECIMAL(6,2) SIGNED',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\DecimalType',
                6,
                2,
                true
            ],
            [
                'DECIMAL(6,2) UNSIGNED',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\DecimalType',
                6,
                2,
                false
            ],
            [
                'FLOAT(6,2)',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\FloatType',
                6,
                2,
                true
            ],
            [
                'FLOAT(6,2) SIGNED',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\FloatType',
                6,
                2,
                true
            ],
            [
                'FLOAT(6,2) UNSIGNED',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\FloatType',
                6,
                2,
                false
            ],
            [
                'DOUBLE(6,2)',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\DoubleType',
                6,
                2,
                true
            ],
            [
                'DOUBLE(6,2) SIGNED',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\DoubleType',
                6,
                2,
                true
            ],
            [
                'DOUBLE(6,2) UNSIGNED',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\DoubleType',
                6,
                2,
                false
            ]
        ];
    }

    /**
     * @see TestNewStringType
     * @return array
     */
    public function stringTypeProvider()
    {
        return [
            [
                'CHAR(3)',
                'utf8_general_ci',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\CharType',
                'utf8',
                3
            ],
            [
                'VARCHAR(3)',
                'utf8_general_ci',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\VarCharType',
                'utf8',
                3
            ],
            [
                'TEXT',
                'utf8_general_ci',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\TextType',
                'utf8',
            ],
            [
                'TINYTEXT',
                'utf8_general_ci',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\TinyTextType',
                'utf8',
            ],
            [
                'MEDIUMTEXT',
                'utf8_general_ci',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\MediumTextType',
                'utf8',
            ],
            [
                'LONGTEXT',
                'utf8_general_ci',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\LongTextType',
                'utf8',
            ]
        ];
    }

    /**
     * @see TestNewBinaryType
     * @return array
     */
    public function binaryTypeProvider()
    {
        return [
            [
                'BINARY(3)',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\BinaryType',
                3
            ],
            [
                'VARBINARY(3)',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\VarBinaryType',
                3
            ],
            [
                'BLOB',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\BlobType'
            ],
            [
                'TINYBLOB',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\TinyBlobType'
            ],
            [
                'MEDIUMBLOB',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\MediumBlobType'
            ],
            [
                'LONGBLOB',
                'MilesAsylum\Schnoop\Schema\MySQL\DataType\LongBlobType'
            ]
        ];
    }

    /**
     * @see testCorrectColumnInstanceForDataType
     * @return array
     */
    public function columnTypeProvider()
    {
        $typeExpectations = [
            [
                'type' => 'INT(3)',
                'expectedColumnInstance' => 'MilesAsylum\Schnoop\Schema\MySQL\Column\NumericColumn'
            ],
            [
                'type' => 'TINYINT(3)',
                'expectedColumnInstance' => 'MilesAsylum\Schnoop\Schema\MySQL\Column\NumericColumn'
            ],
            [
                'type' => 'SMALLINT(3)',
                'expectedColumnInstance' => 'MilesAsylum\Schnoop\Schema\MySQL\Column\NumericColumn'
            ],
            [
                'type' => 'MEDIUMINT(3)',
                'expectedColumnInstance' => 'MilesAsylum\Schnoop\Schema\MySQL\Column\NumericColumn'
            ],
            [
                'type' => 'BIGINT(3)',
                'expectedColumnInstance' => 'MilesAsylum\Schnoop\Schema\MySQL\Column\NumericColumn'
            ],
            [
                'type' => 'DECIMAL(4,1)',
                'expectedColumnInstance' => 'MilesAsylum\Schnoop\Schema\MySQL\Column\NumericColumn'
            ],
            [
                'type' => 'FLOAT(4,1)',
                'expectedColumnInstance' => 'MilesAsylum\Schnoop\Schema\MySQL\Column\NumericColumn'
            ],
            [
                'type' => 'DOUBLE(4,1)',
                'expectedColumnInstance' => 'MilesAsylum\Schnoop\Schema\MySQL\Column\NumericColumn'
            ],
            [
                'type' => 'CHAR(3)',
                'expectedColumnInstance' => 'MilesAsylum\Schnoop\Schema\MySQL\Column\Column'
            ],
            [
                'type' => 'VARCHAR(3)',
                'expectedColumnInstance' => 'MilesAsylum\Schnoop\Schema\MySQL\Column\Column'
            ],
            [
                'type' => 'TEXT',
                'expectedColumnInstance' => 'MilesAsylum\Schnoop\Schema\MySQL\Column\Column'
            ],
            [
                'type' => 'TINYTEXT',
                'expectedColumnInstance' => 'MilesAsylum\Schnoop\Schema\MySQL\Column\Column'
            ],
            [
                'type' => 'MEDIUMTEXT',
                'expectedColumnInstance' => 'MilesAsylum\Schnoop\Schema\MySQL\Column\Column'
            ],
            [
                'type' => 'LONGTEXT',
                'expectedColumnInstance' => 'MilesAsylum\Schnoop\Schema\MySQL\Column\Column'
            ],
            [
                'type' => 'BLOB',
                'expectedColumnInstance' => 'MilesAsylum\Schnoop\Schema\MySQL\Column\Column'
            ],
            [
                'type' => 'TINYBLOB',
                'expectedColumnInstance' => 'MilesAsylum\Schnoop\Schema\MySQL\Column\Column'
            ],
            [
                'type' => 'MEDIUMBLOB',
                'expectedColumnInstance' => 'MilesAsylum\Schnoop\Schema\MySQL\Column\Column'
            ],
            [
                'type' => 'LONGBLOB',
                'expectedColumnInstance' => 'MilesAsylum\Schnoop\Schema\MySQL\Column\Column'
            ],
            [
                'type' => 'BIT(3)',
                'expectedColumnInstance' => 'MilesAsylum\Schnoop\Schema\MySQL\Column\Column'
            ],
            [
                'type' => 'BINARY(3)',
                'expectedColumnInstance' => 'MilesAsylum\Schnoop\Schema\MySQL\Column\Column'
            ],
            [
                'type' => 'VARBINARY(3)',
                'expectedColumnInstance' => 'MilesAsylum\Schnoop\Schema\MySQL\Column\Column'
            ],
        ];

        $standardFields = [
            'field' => 'schnoop_char',
            'collation' => 'utf8_general_ci',
            'null' => 'YES',
            'default' => null,
            'extra' => null,
            'comment' => ''

        ];

        foreach ($typeExpectations as $typeExpectation) {
            $params = $standardFields;
            $params['type'] = $typeExpectation['type'];

            yield [
                $params,
                $typeExpectation['expectedColumnInstance']
            ];
        }
    }
}
