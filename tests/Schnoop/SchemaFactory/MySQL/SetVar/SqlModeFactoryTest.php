<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\SetVar;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\SetVar\SqlModeFactory;
use MilesAsylum\SchnoopSchema\MySQL\SetVar\SqlMode;
use PHPUnit\Framework\TestCase;

class SqlModeFactoryTest extends TestCase
{
    /**
     * @var SqlModeFactory
     */
    protected $sqlModeFactory;

    public function setUp(): void
    {
        parent::setUp();

        $this->sqlModeFactory = new SqlModeFactory();
    }

    public function testNewSqlMode()
    {
        $mode = 'TRANSITIONAL';
        $sqlMode = $this->sqlModeFactory->newSqlMode($mode);

        $this->assertInstanceOf(SqlMode::class, $sqlMode);
        $this->assertSame($mode, $sqlMode->getMode());
    }
}
