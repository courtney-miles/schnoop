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
use MilesAsylum\Schnoop\PHPUnit\Schnoop\MockPdo;
use MilesAsylum\Schnoop\Schema\FactoryInterface;
use MilesAsylum\Schnoop\Schnoop;
use MilesAsylum\Schnoop\SchnoopFactory;
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
    protected $mockSchemaFactory;

    public function setUp()
    {
        parent::setUp();
        
        /** @var \PDO $mockPdo */
        $mockPdo = $this->createMock(MockPdo::class);
        
        $this->mockDbInspector = $this->createMock(DbInspectorInterface::class);
        $this->mockDbInspector->method('fetchActiveDatabase')
            ->willReturn($this->databaseList[0]);
        $this->mockDbInspector->method('fetchDatabaseList')
            ->willReturn($this->databaseList);
        
        $this->mockSchemaFactory = $this->createMock(FactoryInterface::class);

        $this->schnoop = new Schnoop(
            $mockPdo,
            $this->mockDbInspector,
            $this->mockSchemaFactory
        );
    }

    public function testGetActiveDatabaseName()
    {
        $this->assertSame($this->databaseList[0], $this->schnoop->getActiveDatabaseName());
    }

    public function testSetActiveDatabase()
    {
        $this->schnoop->setActiveDatabase($this->databaseList[1]);
        $this->assertSame($this->databaseList[1], $this->schnoop->getActiveDatabaseName());
    }

    /**
     * @expectedException \MilesAsylum\Schnoop\Exception\SchnoopException
     * @expectedExceptionMessage Unknown database, `bogus`.
     */
    public function testSetActiveDatabaseExceptionOnUnknownDatabase()
    {
        $this->schnoop->setActiveDatabase('bogus');
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
        $fetchDb = $this->databaseList[0];
        $fetchDbReturn = array('Something');
        $factoryDbReturn = 'Something_else';

        $this->mockDbInspector->expects($this->atLeastOnce())
            ->method('fetchDatabase')
            ->with($fetchDb)
            ->willReturn($fetchDbReturn);

        $this->mockSchemaFactory->expects($this->atLeastOnce())
            ->method('createDatabase')
            ->with($fetchDbReturn)
            ->willReturn($factoryDbReturn);

        $this->assertSame($factoryDbReturn, $this->schnoop->getDatabase());
    }

    public function testGetTableList()
    {
        $fetchDb = $this->databaseList[0];
        $fetchDbReturn = 'Foo';

        $this->mockDbInspector->expects($this->atLeastOnce())
            ->method('fetchTableList')
            ->with($fetchDb)
            ->willReturn($fetchDbReturn);

        $this->assertSame($fetchDbReturn, $this->schnoop->getTableList());
    }

    public function testGetTable()
    {
        $fetchDb = $this->databaseList[0];
        $fetchTable = 'schnoop_table';
        $fetchTableReturn = ['Foo'];
        $fetchColumnReturn = ['Bar'];
        $fetchIndexReturn = ['Fiz'];
        $newTableReturn = 'Foobar';

        $this->mockDbInspector->expects($this->atLeastOnce())
            ->method('fetchTable')
            ->with($fetchDb, $fetchTable)
            ->willReturn($fetchTableReturn);

        $this->mockDbInspector->expects($this->atLeastOnce())
            ->method('fetchColumns')
            ->with($fetchDb, $fetchTable)
            ->willReturn($fetchColumnReturn);

        $this->mockDbInspector->expects($this->atLeastOnce())
            ->method('fetchIndexes')
            ->with($fetchDb, $fetchTable)
            ->willReturn($fetchIndexReturn);

        $this->mockSchemaFactory->expects($this->atLeastOnce())
            ->method('createTable')
            ->with($fetchTableReturn, $fetchColumnReturn)
            ->willReturn($newTableReturn);

        $this->assertSame($newTableReturn, $this->schnoop->getTable($fetchTable));
    }
}
