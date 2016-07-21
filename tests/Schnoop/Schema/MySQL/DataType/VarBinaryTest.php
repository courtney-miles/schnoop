<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 26/06/16
 * Time: 6:22 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\VarBinaryType;

class VarBinaryTest extends SchnoopTestCase
{
    /**
     * @var VarBinaryType
     */
    protected $varBinaryType;

    protected $length = 3;

    public function setUp()
    {
        parent::setUp();

        $this->varBinaryType = new VarBinaryType($this->length);
    }

    public function testConstruct()
    {
        $this->binaryTypeAsserts(
            DataTypeInterface::TYPE_VARBINARY,
            $this->length,
            true,
            $this->varBinaryType
        );
    }

    public function testCast()
    {
        $this->assertSame('123', $this->varBinaryType->cast(123));
    }
}
