<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 8/06/16
 * Time: 7:59 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\Database;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\Database\Database;
use MilesAsylum\Schnoop\Schnoop;
use PHPUnit_Framework_MockObject_MockObject;

class DatabaseTest extends SchnoopTestCase
{
    /**
     * @var Database
     */
    protected $database;

    /**
     * @var Schnoop|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockSchnoop;

    protected $name = 'schnoop';

    protected $charSet = 'utf8';

    protected $collation = 'utf8_general_ci';

    protected $tableList = [
        'schnoop_table_one',
        'schnoop_table_two'
    ];

    protected function setUp()
    {
        parent::setUp();

        $this->mockSchnoop = $this->createMock('MilesAsylum\Schnoop\Schnoop');
        $this->mockSchnoop->method('getTableList')
            ->willReturn($this->tableList);

        $this->database = new Database($this->name, $this->charSet, $this->collation, $this->mockSchnoop);
    }

    public function testGetName()
    {
        $this->assertSame($this->name, $this->database->getName());
    }

    public function testGetCharacterSet()
    {
        $this->assertSame($this->charSet, $this->database->getDefaultCharacterSet());
    }

    public function testGetCollation()
    {
        $this->assertSame($this->collation, $this->database->getDefaultCollation());
    }

    public function testGetTableList()
    {
        $this->assertSame($this->tableList, $this->database->getTableList());
    }

    public function testGetTable()
    {
        $tableName = 'schnoop_table_one';
        $table = 'Foo';

        $this->mockSchnoop->method('getTable')
            ->with($this->name, $tableName)
            ->willReturn($table);

        $this->assertSame($table, $this->database->getTable($tableName));
    }

    public function testGetTableThatDoesNotExist()
    {
        $this->assertNull($this->database->getTable('Bogus'));
    }
}
