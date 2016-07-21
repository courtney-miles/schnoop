<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 26/06/16
 * Time: 6:11 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\LongBlobType;

class LongBlobTypeTest extends SchnoopTestCase
{
    /**
     * @var LongBlobType
     */
    protected $longBlobType;

    public function setUp()
    {
        parent::setUp();

        $this->longBlobType = new LongBlobType();
    }

    public function testConstruct()
    {
        $this->binaryTypeAsserts(
            DataTypeInterface::TYPE_LONGBLOB,
            pow(2, 32)-1,
            false,
            $this->longBlobType
        );
    }

    public function testCast()
    {
        $this->assertSame('123', $this->longBlobType->cast(123));
    }
}
