<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 8/07/16
 * Time: 4:42 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\SetType;

class SetTypeTest extends SchnoopTestCase
{
    /**
     * @var SetType
     */
    protected $setType;

    protected $options = [
        'foo',
        'bar'
    ];

    protected $charSet = 'utf8';

    protected $collation = 'utf8_general_ci';

    public function setUp()
    {
        parent::setUp();

        $this->setType = new SetType(
            $this->options, $this->collation
        );
    }

    public function testConstruct()
    {
        $this->assertSame(DataTypeInterface::TYPE_SET, $this->setType->getName());
        $this->assertSame($this->options, $this->setType->getOptions());
        $this->assertSame($this->collation, $this->setType->getCollation());
    }

    public function testCast()
    {
        $value = [123];
        $expectedValue = ['123'];

        $this->assertSame($expectedValue, $this->setType->cast($value));
    }
}
