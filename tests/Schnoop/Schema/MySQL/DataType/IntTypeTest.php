<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/06/16
 * Time: 10:49 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\IntType;

class IntTypeTest extends SchnoopTestCase
{
    /**
     * @var IntType
     */
    protected $intTypeSigned;

    /**
     * @var IntType
     */
    protected $intTypeUnsigned;
    
    protected $displayWidth = 4;

    public function setUp()
    {
        parent::setUp();

        $this->intTypeSigned = new IntType($this->displayWidth, true);
        $this->intTypeUnsigned = new IntType($this->displayWidth, false);
    }

    public function testConstructSigned()
    {
        $this->assertIsIntTypeConstruct(
            DataTypeInterface::TYPE_INT,
            $this->displayWidth,
            true,
            -pow(2, 32)/2,
            pow(2, 32)/2-1,
            true,
            $this->intTypeSigned
        );
    }

    public function testConstructUnsigned()
    {
        $this->assertIsIntTypeConstruct(
            DataTypeInterface::TYPE_INT,
            $this->displayWidth,
            false,
            0,
            pow(2, 32)-1,
            true,
            $this->intTypeUnsigned
        );
    }

    public function testCast()
    {
        $this->assertSame(123, $this->intTypeSigned->cast('123'));
    }
}
