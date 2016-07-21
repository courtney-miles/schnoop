<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 23/06/16
 * Time: 7:11 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\TinyTextType;

class TinyTextTypeTest extends SchnoopTestCase
{
    /**
     * @var TinyTextType
     */
    protected $tinyTextType;
    
    protected $characterSet = 'ut8';
    
    protected $collation = 'utf8_general_ci';

    public function setUp()
    {
        parent::setUp();
        
        $this->tinyTextType = new TinyTextType($this->collation);
    }

    public function testConstructed()
    {
        $this->stringTypeAsserts(
            DataTypeInterface::TYPE_TINYTEXT, pow(2, 8) - 1, $this->collation, false, $this->tinyTextType
        );
    }

    public function testCast()
    {
        $this->assertSame('123', $this->tinyTextType->cast(123));
    }
}
