<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 30/06/16
 * Time: 7:13 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop;

use MilesAsylum\Schnoop\DbInspector\MySQLInspector;
use MilesAsylum\Schnoop\SchnoopFactory;
use PDO;
use PHPUnit_Framework_MockObject_MockObject;

class SchnoopFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SchnoopFactory
     */
    protected $schnoopFactory;

    /**
     * @var \PDO|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockPdo;

    public function setUp()
    {
        parent::setUp();

        $this->mockPdo = $this->createMock('MilesAsylum\Schnoop\PHPUnit\Schnoop\MockPdo');

        $this->schnoopFactory = new SchnoopFactory();
    }

    public function testNewDbInspector()
    {
        $this->mockPdo->expects($this->atLeastOnce())
            ->method('getAttribute')
            ->with(PDO::ATTR_DRIVER_NAME)
            ->willReturn('mysql');

        $this->assertInstanceOf(
            '\MilesAsylum\Schnoop\DbInspector\MySQLInspector',
            $this->schnoopFactory->newDBInspector($this->mockPdo)
        );
    }

    public function testNewSchemaFactory()
    {
        /** @var MySQLInspector $mockDbInspector */
        $mockDbInspector = $this->createMock('MilesAsylum\Schnoop\DbInspector\MySQLInspector');

        $this->assertInstanceOf(
            'MilesAsylum\Schnoop\Schema\MySQLFactory',
            $this->schnoopFactory->newSchemaFactory($mockDbInspector, $this->mockPdo)
        );
    }

    public function testCreateSchnoop()
    {
        $mockPdoStatement = $this->createMock('\PDOStatement');

        $this->mockPdo->expects($this->atLeastOnce())
            ->method('getAttribute')
            ->with(PDO::ATTR_DRIVER_NAME)
            ->willReturn('mysql');

        $this->mockPdo->method('query')->willReturn($mockPdoStatement);

        $mockPdoStatement->method('fetchAll')->willReturn(array());

        $this->assertInstanceOf('MilesAsylum\Schnoop\Schnoop', $this->schnoopFactory->create($this->mockPdo));
    }

    /**
     * @expectedException \MilesAsylum\Schnoop\Exception\SchnoopException
     */
    public function testExceptionForUnsupportedDatabaseEngine()
    {
        $this->mockPdo->expects($this->atLeastOnce())
            ->method('getAttribute')
            ->with(PDO::ATTR_DRIVER_NAME)
            ->willReturn('postgresql');
        
        $this->schnoopFactory->newDBInspector($this->mockPdo);
    }
}
