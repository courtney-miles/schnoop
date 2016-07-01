<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 23/06/16
 * Time: 7:25 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\LongTextType;

class LongTextTypeTest extends SchnoopTestCase
{
    /**
     * @var LongTextType
     */
    protected $longTextType;

    protected $characterSet = 'utf8';

    protected $collation = 'utf8_general_ci';

    public function setUp()
    {
        parent::setUp();

        $this->longTextType = new LongTextType(
            $this->characterSet,
            $this->collation
        );
    }

    public function testConstructed()
    {
        $this->assertIsStringTypeConstruct(
            DataTypeInterface::TYPE_LONGTEXT,
            pow(2, 32)-1,
            $this->characterSet,
            $this->collation,
            false,
            $this->longTextType
        );
    }

    public function testCast()
    {
        $this->assertSame('123', $this->longTextType->cast(123));
    }
}
