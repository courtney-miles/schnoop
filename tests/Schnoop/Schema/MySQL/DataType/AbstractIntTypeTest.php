<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/06/16
 * Time: 10:34 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\AbstractIntType;
use PHPUnit_Framework_MockObject_MockObject;

class AbstractIntTypeTest extends SchnoopTestCase
{
    /**
     * @var AbstractIntType|PHPUnit_Framework_MockObject_MockObject
     */
    protected $abstractIntType;

    protected $type = 'int';

    protected $displayWidth = '3';

    protected $signed = true;

    protected $minRange = -128;

    protected $maxRange = 127;

    public function setUp()
    {
        parent::setUp();

        $this->abstractIntType = $this->getMockForAbstractClass(
            'MilesAsylum\Schnoop\Schema\MySQL\DataType\AbstractIntType',
            [
                $this->displayWidth,
                $this->signed,
                $this->minRange,
                $this->maxRange
            ]
        );

        $this->abstractIntType->expects($this->any())
            ->method('getName')
            ->willReturn($this->type);
    }

    public function testConstruct()
    {
        $this->intTypeAsserts(
            $this->type,
            (int)$this->displayWidth,
            $this->signed,
            $this->minRange,
            $this->maxRange,
            true,
            $this->abstractIntType
        );
    }

    public function testCast()
    {
        $this->assertSame(123, $this->abstractIntType->cast('123'));
    }
}
