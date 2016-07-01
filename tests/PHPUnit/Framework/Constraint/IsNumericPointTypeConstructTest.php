<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 21/06/16
 * Time: 7:22 AM
 */

namespace MilesAsylum\Schnoop\Tests\PHPUnit\Framework\Constraint;

use MilesAsylum\Schnoop\PHPUnit\Framework\Constraint\IsNumericPointTypeConstruct;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\NumericPointTypeInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class IsNumericPointTypeConstructTest extends TestCase
{
    /**
     * @var IsNumericPointTypeConstruct
     */
    protected $isSuccesfulNumericPointConstruct;

    /**
     * @var NumericPointTypeInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockNumericPointType;
    
    protected $type = 'decimal';
    
    protected $precision = 6;

    protected $scale = 2;
    
    protected $signed = true;

    protected $minRange = '-9999.99';

    protected $maxRange = '9999.99';

    protected $allowDefault = true;

    public function setUp()
    {
        parent::setUp();

        $this->isSuccesfulNumericPointConstruct = new IsNumericPointTypeConstruct(
            $this->type,
            $this->precision,
            $this->scale,
            $this->signed,
            $this->minRange,
            $this->maxRange,
            $this->allowDefault
        );

        $this->mockNumericPointType = $this->createMock(
            'MilesAsylum\Schnoop\Schema\MySQL\DataType\NumericPointTypeInterface'
        );
    }

    public function testSuccess()
    {
        $this->setMockNumericPointTypeMethods(
            $this->mockNumericPointType,
            $this->type,
            $this->precision,
            $this->scale,
            $this->signed,
            $this->minRange,
            $this->maxRange,
            $this->allowDefault
        );

        $this->assertTrue($this->isSuccesfulNumericPointConstruct->matches($this->mockNumericPointType));
    }

    public function testFailType()
    {
        $this->setMockNumericPointTypeMethods(
            $this->mockNumericPointType,
            null,
            $this->precision,
            $this->scale,
            $this->signed,
            $this->minRange,
            $this->maxRange,
            $this->allowDefault
        );

        $this->assertFalse($this->isSuccesfulNumericPointConstruct->matches($this->mockNumericPointType));
        $this->assertSame(
            'numeric has correct type',
            $this->isSuccesfulNumericPointConstruct->toString()
        );
    }

    public function testFailPrecision()
    {
        $this->setMockNumericPointTypeMethods(
            $this->mockNumericPointType,
            $this->type,
            null,
            $this->scale,
            $this->signed,
            $this->minRange,
            $this->maxRange,
            $this->allowDefault
        );

        $this->assertFalse($this->isSuccesfulNumericPointConstruct->matches($this->mockNumericPointType));
        $this->assertSame(
            'numeric has correct precision',
            $this->isSuccesfulNumericPointConstruct->toString()
        );
    }

    public function testFailScale()
    {
        $this->setMockNumericPointTypeMethods(
            $this->mockNumericPointType,
            $this->type,
            $this->precision,
            null,
            $this->signed,
            $this->minRange,
            $this->maxRange,
            $this->allowDefault
        );

        $this->assertFalse($this->isSuccesfulNumericPointConstruct->matches($this->mockNumericPointType));
        $this->assertSame(
            'numeric has correct scale',
            $this->isSuccesfulNumericPointConstruct->toString()
        );
    }

    public function testFailSigned()
    {
        $this->setMockNumericPointTypeMethods(
            $this->mockNumericPointType,
            $this->type,
            $this->precision,
            $this->scale,
            null,
            $this->minRange,
            $this->maxRange,
            $this->allowDefault
        );

        $this->assertFalse($this->isSuccesfulNumericPointConstruct->matches($this->mockNumericPointType));
        $this->assertSame(
            'numeric has correct sign',
            $this->isSuccesfulNumericPointConstruct->toString()
        );
    }

    public function testFailMinRange()
    {
        $this->setMockNumericPointTypeMethods(
            $this->mockNumericPointType,
            $this->type,
            $this->precision,
            $this->scale,
            $this->signed,
            null,
            $this->maxRange,
            $this->allowDefault
        );

        $this->assertFalse($this->isSuccesfulNumericPointConstruct->matches($this->mockNumericPointType));
        $this->assertSame(
            'numeric has correct minRange',
            $this->isSuccesfulNumericPointConstruct->toString()
        );
    }

    public function testFailMaxRange()
    {
        $this->setMockNumericPointTypeMethods(
            $this->mockNumericPointType,
            $this->type,
            $this->precision,
            $this->scale,
            $this->signed,
            $this->minRange,
            null,
            $this->allowDefault
        );

        $this->assertFalse($this->isSuccesfulNumericPointConstruct->matches($this->mockNumericPointType));
        $this->assertSame(
            'numeric has correct maxRange',
            $this->isSuccesfulNumericPointConstruct->toString()
        );
    }

    public function testFailAllowDefault()
    {
        $this->setMockNumericPointTypeMethods(
            $this->mockNumericPointType,
            $this->type,
            $this->precision,
            $this->scale,
            $this->signed,
            $this->minRange,
            $this->maxRange,
            null
        );

        $this->assertFalse($this->isSuccesfulNumericPointConstruct->matches($this->mockNumericPointType));
        $this->assertSame(
            'numeric has correct allowDefault',
            $this->isSuccesfulNumericPointConstruct->toString()
        );
    }

    /**
     * @param PHPUnit_Framework_MockObject_MockObject $mockNumericPointType
     * @param null|string $type
     * @param null|int $precision
     * @param null|int $scale
     * @param null|bool $signed
     * @param $minRange
     * @param $maxRange
     * @param $allowDefault
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function setMockNumericPointTypeMethods(
        $mockNumericPointType,
        $type,
        $precision,
        $scale,
        $signed,
        $minRange,
        $maxRange,
        $allowDefault
    ) {
        $mockNumericPointType->expects($this->any())
            ->method('getType')
            ->willReturn($type);
        
        $mockNumericPointType->expects($this->any())
            ->method('getPrecision')
            ->willReturn($precision);

        $mockNumericPointType->expects($this->any())
            ->method('getScale')
            ->willReturn($scale);

        $mockNumericPointType->expects($this->any())
            ->method('isSigned')
            ->willReturn($signed);

        $mockNumericPointType->expects($this->any())
            ->method('getMinRange')
            ->willReturn($minRange);

        $mockNumericPointType->expects($this->any())
            ->method('getMaxRange')
            ->willReturn($maxRange);

        $mockNumericPointType->expects($this->any())
            ->method('allowDefault')
            ->willReturn($allowDefault);

        return $mockNumericPointType;
    }
}
