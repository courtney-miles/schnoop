<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 22/06/16
 * Time: 7:42 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\VarCharType;

class VarCharTypeTest extends SchnoopTestCase
{
    /**
     * @var VarCharType
     */
    protected $varCharType;
    
    protected $length = 10;
    
    protected $characterSet = 'utf8';
    
    protected $collation = 'utf8_general_ci';
    
    public function setUp()
    {
        parent::setUp();
        
        $this->varCharType = new VarCharType(
            $this->length,
            $this->characterSet,
            $this->collation
        );
    }

    public function testConstructed()
    {
        $this->assertIsStringTypeConstruct(
            DataTypeInterface::TYPE_VARCHAR,
            $this->length,
            $this->characterSet,
            $this->collation,
            true,
            $this->varCharType
        );
    }

    public function testCastToString()
    {
        $this->assertSame('123', $this->varCharType->cast(123));
    }
}
