<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 29/06/16
 * Time: 6:54 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop;

use MilesAsylum\Schnoop\DbInspector\DbInspectorInterface;
use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\FactoryInterface;
use MilesAsylum\Schnoop\Schnoop;
use PHPUnit_Framework_MockObject_MockObject;

class SchnoopTest extends SchnoopTestCase
{
    /**
     * @var Schnoop
     */
    protected $schnoop;
    
    protected $databaseList = [
        'schnoop_db_one',
        'schnoop_db_two'
    ];

    /**
     * @var DbInspectorInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockDbInspector;

    /**
     * @var FactoryInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockFactory;
    
    public function setUp()
    {
        parent::setUp();
        
        /** @var \PDO $mockPdo */
        $mockPdo = $this->createMock('MilesAsylum\Schnoop\PHPUnit\Schnoop\MockPdo');
        
        $this->mockDbInspector = $this->createMock('MilesAsylum\Schnoop\DbInspector\DbInspectorInterface');
        $this->mockDbInspector->method('fetchDatabaseList')
            ->willReturn($this->databaseList);
        
        $this->mockFactory = $this->createMock('MilesAsylum\Schnoop\Schema\FactoryInterface');
        
        $this->schnoop = new Schnoop(
            $mockPdo,
            $this->mockDbInspector,
            $this->mockFactory
        );
    }

    public function testDatabaseList()
    {
        $this->assertSame(array_values($this->databaseList), $this->schnoop->getDatabaseList());
    }

    public function testHasDatabase()
    {
        $this->assertTrue($this->schnoop->hasDatabase('schnoop_db_one'));
    }

    public function testHasDatabaseNot()
    {
        $this->assertFalse($this->schnoop->hasDatabase('bogus'));
    }

    public function testGetDatabase()
    {
        $fetchDb = 'schnoop_db_one';
        $fetchDbReturn = array('Something');
        $factoryDbReturn = 'Something_else';

        $this->mockDbInspector->expects($this->atLeastOnce())
            ->method('fetchDatabase')
            ->with($fetchDb)
            ->willReturn($fetchDbReturn);

        $this->mockFactory->expects($this->atLeastOnce())
            ->method('createDatabase')
            ->with($fetchDbReturn, $this->schnoop)
            ->willReturn($factoryDbReturn);

        $this->assertSame($factoryDbReturn, $this->schnoop->getDatabase($fetchDb));
    }

    public function testGetDatabaseNot()
    {
        $this->assertNull($this->schnoop->getDatabase('bogus'));
    }

    public function testGetTableList()
    {
        $fetchDb = 'schnoop_db_one';
        $fetchDbReturn = 'Foo';

        $this->mockDbInspector->expects($this->atLeastOnce())
            ->method('fetchTableList')
            ->with($fetchDb)
            ->willReturn($fetchDbReturn);

        $this->assertSame($fetchDbReturn, $this->schnoop->getTableList($fetchDb));
    }

    public function testGetTable()
    {
        $fetchDb = 'schnoop_db_one';
        $fetchTable = 'schnoop_table';
        $fetchTableReturn = ['Foo'];
        $fetchColumnReturn = ['Bar'];
        $newTableReturn = 'Foobar';

        $this->mockDbInspector->expects($this->atLeastOnce())
            ->method('fetchTable')
            ->with($fetchDb, $fetchTable)
            ->willReturn($fetchTableReturn);

        $this->mockDbInspector->expects($this->atLeastOnce())
            ->method('fetchColumns')
            ->with($fetchDb, $fetchTable)
            ->willReturn($fetchColumnReturn);

        $this->mockFactory->expects($this->atLeastOnce())
            ->method('createTable')
            ->with($fetchTableReturn, $fetchColumnReturn)
            ->willReturn($newTableReturn);

        $this->assertSame($newTableReturn, $this->schnoop->getTable($fetchDb, $fetchTable));
    }
}
