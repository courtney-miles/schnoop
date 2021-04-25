<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaAdapter;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\Table;
use MilesAsylum\Schnoop\Schnoop;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TableTest extends TestCase
{
    protected $name = 'schnoop_tbl';

    protected $databaseName = 'schnoop_db';

    /**
     * @var Table
     */
    protected $table;

    /**
     * @var Schnoop|MockObject
     */
    protected $mockSchnoop;

    public function setUp()
    {
        parent::setUp();

        $this->mockSchnoop = $this->createMock(Schnoop::class);

        $this->table = new Table($this->databaseName, $this->name);
        $this->table->setSchnoop($this->mockSchnoop);
    }

    public function testGetTriggers()
    {
        $expectedTriggers = ['foo'];

        $this->mockSchnoop->expects($this->once())
            ->method('getTriggers')
            ->with($this->name, $this->databaseName)
            ->willReturn($expectedTriggers);

        $this->assertSame($expectedTriggers, $this->table->getTriggers());
    }

    public function testHasTriggers()
    {
        $this->mockSchnoop->expects($this->once())
            ->method('hasTriggers')
            ->with($this->name, $this->databaseName)
            ->willReturn(true);

        $this->assertTrue($this->table->hasTriggers());
    }
}
