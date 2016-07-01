<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 22/06/16
 * Time: 4:44 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;


use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\BigIntType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;

class BigIntTypeTest extends SchnoopTestCase
{
    /**
     * @var BigIntType
     */
    protected $bigIntTypeSigned;

    /**
     * @var BigIntType
     */
    protected $bigIntTypeUnsigned;

    protected $displayWidth = 10;

    public function setUp()
    {
        parent::setUp();

        $this->bigIntTypeSigned = new BigIntType(
            $this->displayWidth,
            true
        );

        $this->bigIntTypeUnsigned = new BigIntType(
            $this->displayWidth,
            false
        );
    }

    public function testConstructedSigned()
    {
        $this->assertIsIntTypeConstruct(
            DataTypeInterface::TYPE_BIGINT,
            $this->displayWidth,
            true,
            (int)('-' . bcdiv(bcpow('2', '64'), '2')),
            (int)bcsub(bcdiv(bcpow('2', '64'), '2'), '1'),
            true,
            $this->bigIntTypeSigned
        );
    }

    public function testConstructedUnsigned()
    {
        $this->assertIsIntTypeConstruct(
            DataTypeInterface::TYPE_BIGINT,
            $this->displayWidth,
            false,
            0,
            (float)bcsub(bcpow('2', '64'), '1'),
            true,
            $this->bigIntTypeUnsigned
        );
    }
    
    public function testCast()
    {
        $this->assertSame(123, $this->bigIntTypeSigned->cast('123'));
    }
}
