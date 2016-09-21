<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\SetVar;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\SetVar\SqlModeFactory;
use MilesAsylum\SchnoopSchema\MySQL\SetVar\SqlMode;

class SqlModeFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SqlModeFactory
     */
    protected $sqlModeFactory;

    public function setUp()
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
