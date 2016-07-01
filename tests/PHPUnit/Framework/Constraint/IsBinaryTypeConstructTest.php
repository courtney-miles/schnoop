<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 22/06/16
 * Time: 7:26 AM
 */

namespace MilesAsylum\Schnoop\Tests\PHPUnit\Framework\Constraint;

use MilesAsylum\Schnoop\PHPUnit\Framework\Constraint\IsBinaryTypeConstruct;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\BinaryTypeInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class IsBinaryTypeConstructTest extends TestCase
{
    /**
     * @var string
     */
    protected $type = 'binary';

    /**
     * @var IsBinaryTypeConstruct
     */
    protected $constraint;

    /**
     * @var BinaryTypeInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockBinaryType;

    /**
     * @var int
     */
    protected $length = 123;

    protected $allowDefault = true;

    public function setUp()
    {
        parent::setUp();

        $this->constraint = new IsBinaryTypeConstruct(
            $this->type,
            $this->length,
            $this->allowDefault
        );
        
        $this->mockBinaryType = $this->createMock(
            'MilesAsylum\Schnoop\Schema\MySQL\DataType\BinaryTypeInterface'
        );
    }

    public function testSuccess()
    {
        $this->setMockBinaryTypeMethods(
            $this->mockBinaryType,
            $this->type,
            $this->length,
            $this->allowDefault
        );

        $this->assertTrue($this->constraint->matches($this->mockBinaryType));
    }

    public function testFailType()
    {
        $this->setMockBinaryTypeMethods(
            $this->mockBinaryType,
            null,
            $this->length,
            $this->allowDefault
        );

        $this->assertFalse($this->constraint->matches($this->mockBinaryType));
        $this->assertSame('string has correct type', $this->constraint->toString());
    }

    public function testFailLength()
    {
        $this->setMockBinaryTypeMethods(
            $this->mockBinaryType,
            $this->type,
            null,
            $this->allowDefault
        );
        
        $this->assertFalse($this->constraint->matches($this->mockBinaryType));
        $this->assertSame('string has correct length', $this->constraint->toString());
    }

    public function testFailAllowDefault()
    {
        $this->setMockBinaryTypeMethods(
            $this->mockBinaryType,
            $this->type,
            $this->length,
            null
        );

        $this->assertFalse($this->constraint->matches($this->mockBinaryType));
        $this->assertSame('string has correct allowDefault', $this->constraint->toString());
    }

    /**
     * @param PHPUnit_Framework_MockObject_MockObject $mockBinaryType
     * @param null|string $type
     * @param null|int $length
     * @param null|bool $allowNull
     * @return BinaryTypeInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected function setMockBinaryTypeMethods(
        PHPUnit_Framework_MockObject_MockObject $mockBinaryType,
        $type,
        $length,
        $allowNull
    ) {
        $mockBinaryType->expects($this->any())
            ->method('getType')
            ->willReturn($type);

        $mockBinaryType->expects($this->any())
            ->method('getLength')
            ->willReturn($length);

        $mockBinaryType->expects($this->any())
            ->method('allowDefault')
            ->willReturn($allowNull);

        return $mockBinaryType;
    }
}
