<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/06/16
 * Time: 9:52 PM
 */

namespace MilesAsylum\Schnoop\Tests\PHPUnit\Framework\Constraint;

use MilesAsylum\Schnoop\PHPUnit\Framework\Constraint\IsIntTypeConstruct;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\AbstractIntType;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class IsIntTypeConstructTest extends TestCase
{
    /**
     * @var IsIntTypeConstruct
     */
    protected $isIntConstruct;

    /**
     * @var AbstractIntType|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockIntType;

    protected $type = 'int';

    protected $displayWidth = 4;

    protected $signed = true;

    protected $minRange = -128;

    protected $maxRange = 127;

    protected $allowDefault = true;

    public function setUp()
    {
        parent::setUp();

        $this->isIntConstruct = new IsIntTypeConstruct(
            $this->type,
            $this->displayWidth,
            $this->signed,
            $this->minRange,
            $this->maxRange,
            $this->allowDefault
        );

        $this->mockIntType = $this->createMock(
            'MilesAsylum\Schnoop\Schema\MySQL\DataType\AbstractIntType'
        );
    }

    public function testSuccess()
    {
        $this->setMockIntTypeMethods(
            $this->mockIntType,
            $this->type,
            $this->displayWidth,
            $this->signed,
            $this->minRange,
            $this->maxRange,
            $this->allowDefault
        );

        $this->assertTrue($this->isIntConstruct->matches($this->mockIntType));
    }
    
    public function testFailType()
    {
        $this->setMockIntTypeMethods(
            $this->mockIntType,
            null,
            $this->displayWidth,
            $this->signed,
            $this->minRange,
            $this->maxRange,
            $this->allowDefault
        );

        $this->assertFalse($this->isIntConstruct->matches($this->mockIntType));
        $this->assertSame('int has correct type', $this->isIntConstruct->toString());
    }

    public function testFailDisplayWidth()
    {
        $this->setMockIntTypeMethods(
            $this->mockIntType,
            $this->type,
            null,
            $this->signed,
            $this->minRange,
            $this->maxRange,
            $this->allowDefault
        );

        $this->assertFalse($this->isIntConstruct->matches($this->mockIntType));
        $this->assertSame('int has correct displayWidth', $this->isIntConstruct->toString());
    }

    public function testFailSigned()
    {
        $this->setMockIntTypeMethods(
            $this->mockIntType,
            $this->type,
            $this->displayWidth,
            null,
            $this->minRange,
            $this->maxRange,
            $this->allowDefault
        );

        $this->assertFalse($this->isIntConstruct->matches($this->mockIntType));
        $this->assertSame('int has correct sign', $this->isIntConstruct->toString());
    }

    public function testFailMinRange()
    {
        $this->setMockIntTypeMethods(
            $this->mockIntType,
            $this->type,
            $this->displayWidth,
            $this->signed,
            null,
            $this->maxRange,
            $this->allowDefault
        );

        $this->assertFalse($this->isIntConstruct->matches($this->mockIntType));
        $this->assertSame('int has correct minRange', $this->isIntConstruct->toString());
    }

    public function testFailMaxRange()
    {
        $this->setMockIntTypeMethods(
            $this->mockIntType,
            $this->type,
            $this->displayWidth,
            $this->signed,
            $this->minRange,
            null,
            $this->allowDefault
        );

        $this->assertFalse($this->isIntConstruct->matches($this->mockIntType));
        $this->assertSame('int has correct maxRange', $this->isIntConstruct->toString());
    }

    public function testFailAllowNull()
    {
        $this->setMockIntTypeMethods(
            $this->mockIntType,
            $this->type,
            $this->displayWidth,
            $this->signed,
            $this->minRange,
            $this->maxRange,
            null
        );

        $this->assertFalse($this->isIntConstruct->matches($this->mockIntType));
        $this->assertSame('int has correct allowDefault', $this->isIntConstruct->toString());
    }

    /**
     * @param PHPUnit_Framework_MockObject_MockObject $mockIntType
     * @param null|string $type
     * @param null|int $displayWidth
     * @param null|bool $signed
     * @param null|int $minRange
     * @param null|int $maxRange
     * @param null|bool $allowDefault
     */
    protected function setMockIntTypeMethods(
        PHPUnit_Framework_MockObject_MockObject $mockIntType,
        $type,
        $displayWidth,
        $signed,
        $minRange,
        $maxRange,
        $allowDefault
    ) {
        $mockIntType->expects($this->any())
            ->method('getType')
            ->willReturn($type);

        $mockIntType->expects($this->any())
            ->method('getDisplayWidth')
            ->willReturn($displayWidth);

        $mockIntType->expects($this->any())
            ->method('isSigned')
            ->willReturn($signed);

        $mockIntType->expects($this->any())
            ->method('getMinRange')
            ->willReturn($minRange);

        $mockIntType->expects($this->any())
            ->method('getMaxRange')
            ->willReturn($maxRange);

        $mockIntType->expects($this->any())
            ->method('allowDefault')
            ->willReturn($allowDefault);
    }
}
