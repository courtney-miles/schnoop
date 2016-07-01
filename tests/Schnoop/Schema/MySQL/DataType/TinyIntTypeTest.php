<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 26/06/16
 * Time: 6:20 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\TinyIntType;

class TinyIntTypeTest extends SchnoopTestCase
{
    /**
     * @var TinyIntType
     */
    protected $tinyIntTypeSigned;

    /**
     * @var TinyIntType
     */
    protected $tinyIntTypeUnsigned;

    protected $displayWidth = 4;

    public function setUp()
    {
        parent::setUp();

        $this->tinyIntTypeSigned = new TinyIntType($this->displayWidth, true);
        $this->tinyIntTypeUnsigned = new TinyIntType($this->displayWidth, false);
    }

    public function testConstructSigned()
    {
        $this->assertIsIntTypeConstruct(
            DataTypeInterface::TYPE_TINYINT,
            $this->displayWidth,
            true,
            -pow(2, 8)/2,
            pow(2, 8)/2-1,
            true,
            $this->tinyIntTypeSigned
        );
    }

    public function testConstructUnsigned()
    {
        $this->assertIsIntTypeConstruct(
            DataTypeInterface::TYPE_TINYINT,
            $this->displayWidth,
            false,
            0,
            pow(2, 8)-1,
            true,
            $this->tinyIntTypeUnsigned
        );
    }

    public function testCast()
    {
        $this->assertSame(123, $this->tinyIntTypeSigned->cast('123'));
    }
}
