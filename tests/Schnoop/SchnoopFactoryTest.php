<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 30/06/16
 * Time: 7:13 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop;

use MilesAsylum\Schnoop\DbInspector\MySQLInspector;
use MilesAsylum\Schnoop\PHPUnit\Schnoop\MockPdo;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\MySQLFactory;
use MilesAsylum\Schnoop\Schnoop;
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

        $this->mockPdo = $this->createMock(MockPdo::class);

        $this->schnoopFactory = new SchnoopFactory();
    }

    public function testNewDbInspector()
    {
        $this->mockPdo->expects($this->atLeastOnce())
            ->method('getAttribute')
            ->with(PDO::ATTR_DRIVER_NAME)
            ->willReturn('mysql');

        $this->assertInstanceOf(
            MySQLInspector::class,
            $this->schnoopFactory->newDBInspector($this->mockPdo)
        );
    }

    public function testNewSchemaFactory()
    {
        /** @var MySQLInspector $mockDbInspector */
        $mockDbInspector = $this->createMock(MySQLInspector::class);

        $this->assertInstanceOf(
            MySQLFactory::class,
            $this->schnoopFactory->newSchemaFactory($mockDbInspector, $this->mockPdo)
        );
    }

    public function testCreateSchnoop()
    {
        /** @var SchnoopFactory|PHPUnit_Framework_MockObject_MockObject $schnoopFactory */
        $schnoopFactory = $this->getMockBuilder(SchnoopFactory::class)
            ->setMethods(['newDBInspector', 'newSchemaFactory'])
            ->getMock();
        $schnoopFactory->method('newDBInspector')
            ->willReturn($this->createMock(MySQLInspector::class));
        $schnoopFactory->method('newSchemaFactory')
            ->willReturn($this->createMock(MySQLFactory::class));

        $mockPdoStatement = $this->createMock('\PDOStatement');

        $this->mockPdo->expects($this->atLeastOnce())
            ->method('getAttribute')
            ->with(PDO::ATTR_DRIVER_NAME)
            ->willReturn('mysql');

        $this->mockPdo->method('query')->willReturn($mockPdoStatement);

        $mockPdoStatement->method('fetchAll')->willReturn(array());

        $this->assertInstanceOf(Schnoop::class, $schnoopFactory->create($this->mockPdo));
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
