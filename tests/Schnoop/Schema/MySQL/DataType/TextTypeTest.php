<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 21/06/16
 * Time: 10:04 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\TextType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\TextTypeInterface;

class TextTypeTest extends SchnoopTestCase
{
    /**
     * @var TextTypeInterface
     */
    protected $textType;

    protected $characterSet = 'utf8';

    protected $collation = 'utf8_general_ci';

    public function setUp()
    {
        parent::setUp();

        $this->textType = new TextType(
            $this->collation
        );
    }

    public function testConstructed()
    {
        $this->stringTypeAsserts(
            DataTypeInterface::TYPE_TEXT,
            pow(2, 16) - 1,
            $this->collation,
            false,
            $this->textType
        );
    }

    public function testCast()
    {
        $this->assertSame('123', $this->textType->cast(123));
    }
}
