<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 29/06/16
 * Time: 7:11 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\AbstractDatabase;

class AbstractCommonDatabaseTest extends SchnoopTestCase
{
    /**
     * @var AbstractDatabase
     */
    protected $abstractCommonDatabase;

    protected $name = 'schnoop_database';

    public function setUp()
    {
        parent::setUp();

        $this->abstractCommonDatabase = $this->getMockForAbstractClass(
            AbstractDatabase::class,
            [$this->name]
        );
    }

    public function testConstruct()
    {
        $this->assertSame($this->name, $this->abstractCommonDatabase->getName());
    }
}
