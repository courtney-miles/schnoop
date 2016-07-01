<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 28/06/16
 * Time: 8:09 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\AbstractBinaryType;

class AbstractBinaryTypeTest extends SchnoopTestCase
{
    /**
     * @var AbstractBinaryType
     */
    protected $abstractBinaryType;

    protected $length = '3';

    public function setUp()
    {
        parent::setUp();

        $this->abstractBinaryType = $this->getMockForAbstractClass(
            'MilesAsylum\Schnoop\Schema\MySQL\DataType\AbstractBinaryType',
            [
                $this->length
            ]
        );
    }

    public function testConstruct()
    {
        $this->assertSame((int)$this->length, $this->abstractBinaryType->getLength());
    }

    public function testCast()
    {
        $this->assertSame('123', $this->abstractBinaryType->cast(123));
    }
}
