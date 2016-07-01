<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 26/06/16
 * Time: 6:02 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\CharType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;

class CharTypeTest extends SchnoopTestCase
{
    /**
     * @var CharType
     */
    protected $charType;

    protected $length = 10;

    protected $characterSet = 'utf8';

    protected $collation = 'utf8_general_ci';

    public function setUp()
    {
        parent::setUp();

        $this->charType = new CharType(
            $this->length,
            $this->characterSet,
            $this->collation
        );
    }

    public function testConstructed()
    {
        $this->assertIsStringTypeConstruct(
            DataTypeInterface::TYPE_CHAR,
            $this->length,
            $this->characterSet,
            $this->collation,
            true,
            $this->charType
        );
    }

    public function testCastToString()
    {
        $this->assertSame('123', $this->charType->cast(123));
    }
}
