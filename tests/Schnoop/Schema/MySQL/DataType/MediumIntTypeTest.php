<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 26/06/16
 * Time: 6:14 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;


use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\MediumIntType;

class MediumIntTypeTest extends SchnoopTestCase
{
    /**
     * @var MediumIntType
     */
    protected $intTypeSigned;

    /**
     * @var MediumIntType
     */
    protected $intTypeUnsigned;

    protected $displayWidth = 4;

    public function setUp()
    {
        parent::setUp();

        $this->intTypeSigned = new MediumIntType($this->displayWidth, true);
        $this->intTypeUnsigned = new MediumIntType($this->displayWidth, false);
    }

    public function testConstructSigned()
    {
        $this->intTypeAsserts(
            DataTypeInterface::TYPE_MEDIUMINT,
            $this->displayWidth,
            true,
            -pow(2, 24)/2,
            pow(2, 24)/2-1,
            true,
            $this->intTypeSigned
        );
    }

    public function testConstructUnsigned()
    {
        $this->intTypeAsserts(
            DataTypeInterface::TYPE_MEDIUMINT,
            $this->displayWidth,
            false,
            0,
            pow(2, 24)-1,
            true,
            $this->intTypeUnsigned
        );
    }

    public function testCast()
    {
        $this->assertSame(123, $this->intTypeSigned->cast('123'));
    }
}
