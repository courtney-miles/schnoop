<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 26/06/16
 * Time: 6:16 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\SmallIntType;

class SmallIntTypeTest extends SchnoopTestCase
{
    /**
     * @var SmallIntType
     */
    protected $smallIntTypeSigned;

    /**
     * @var SmallIntType
     */
    protected $smallIntTypeUnsigned;

    protected $displayWidth = 4;

    public function setUp()
    {
        parent::setUp();

        $this->smallIntTypeSigned = new SmallIntType($this->displayWidth, true);
        $this->smallIntTypeUnsigned = new SmallIntType($this->displayWidth, false);
    }

    public function testConstructSigned()
    {
        $this->assertIsIntTypeConstruct(
            DataTypeInterface::TYPE_SMALLINT,
            $this->displayWidth,
            true,
            -pow(2, 16)/2,
            pow(2, 16)/2-1,
            true,
            $this->smallIntTypeSigned
        );
    }

    public function testConstructUnsigned()
    {
        $this->assertIsIntTypeConstruct(
            DataTypeInterface::TYPE_SMALLINT,
            $this->displayWidth,
            false,
            0,
            pow(2, 16)-1,
            true,
            $this->smallIntTypeUnsigned
        );
    }

    public function testCast()
    {
        $this->assertSame(123, $this->smallIntTypeSigned->cast('123'));
    }
}
