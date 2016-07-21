<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 23/06/16
 * Time: 7:22 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\MediumTextType;

class MediumTextTypeTest extends SchnoopTestCase
{
    /**
     * @var MediumTextType
     */
    protected $mediumTextType;

    protected $characterSet = 'utf8';

    protected $collation = 'utf8_general_ci';

    public function setUp()
    {
        parent::setUp();

        $this->mediumTextType = new MediumTextType(
            $this->collation
        );
    }

    public function testConstructed()
    {
        $this->stringTypeAsserts(
            DataTypeInterface::TYPE_MEDIUMTEXT, pow(2, 24) - 1, $this->collation, false, $this->mediumTextType
        );
    }

    public function testCast()
    {
        $this->assertSame('123', $this->mediumTextType->cast(123));
    }
}
