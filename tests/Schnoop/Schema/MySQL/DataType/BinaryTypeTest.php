<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 26/06/16
 * Time: 5:58 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\BinaryType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;

class BinaryTypeTest extends SchnoopTestCase
{
    /**
     * @var BinaryType
     */
    protected $binaryType;

    protected $length = 3;

    public function setUp()
    {
        parent::setUp();

        $this->binaryType = new BinaryType($this->length);
    }

    public function testConstruct()
    {
        $this->assertIsBinaryTypeConstruct(
            DataTypeInterface::TYPE_BINARY,
            $this->length,
            true,
            $this->binaryType
        );
    }

    public function testCast()
    {
        $this->assertSame('123', $this->binaryType->cast(123));
    }
}
