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
            $this->mysqlFactory->createDatabase($rawDatabaseData, $mockSchnoop)
        );
    }

    public function testCreateDataType()
    {
        $collation = 'utf8_general_ci';
        $charType = $this->mysqlFactory->createDataType('char(23)', $collation);

        $this->assertInstanceOf('MilesAsylum\Schnoop\Schema\MySQL\DataType\CharType', $charType);
        $this->assertSame($collation, $charType->getCollation());
    }

    /**
     * @expectedException \MilesAsylum\Schnoop\Schema\Exception\FactoryException
     */
    public function testExceptionOnCreateUnknownType()
    {
        $bogusType = $this->mysqlFactory->createDataType('bogus(23)', 'utf8_general_ci');
    }

    public function testCreateColumn()
    {
        $column = $this->mysqlFactory->createColumn(
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
        $this->assertSame(true, $column->doesAllowNull());
        $this->assertSame('123', $column->getDefault());
        $this->assertSame('Schnoop comment', $column->getComment());
    }

    public function testCreateTable()
    {
        $name = 'schnoop_tbl';
        $engine = 'InnoDB';
        $rowFormat = 'compact';
        $defaultCollation = 'utf8_general_ci';
        $comment = 'Schnoop comment';
        $columnName = 'schnoop_int';

        $table = $this->mysqlFactory->createTable(
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
}
