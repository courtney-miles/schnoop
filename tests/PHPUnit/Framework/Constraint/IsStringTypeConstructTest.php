<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 22/06/16
 * Time: 7:26 AM
 */

namespace MilesAsylum\Schnoop\Tests\PHPUnit\Framework\Constraint;

use MilesAsylum\Schnoop\PHPUnit\Framework\Constraint\IsStringTypeConstruct;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\StringTypeInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class IsStringTypeConstructTest extends TestCase
{
    /**
     * @var string
     */
    protected $type = 'char';

    /**
     * @var IsStringTypeConstruct
     */
    protected $constraint;

    /**
     * @var StringTypeInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockStringType;

    /**
     * @var int
     */
    protected $length = 123;

    /**
     * @var string
     */
    protected $characterSet = 'utf8';

    /**
     * @var string
     */
    protected $collation = 'utf8_general_ci';

    /**
     * @var bool
     */
    protected $allowDefault = true;

    public function setUp()
    {
        parent::setUp();

        $this->constraint = new IsStringTypeConstruct(
            $this->type,
            $this->length,
            $this->characterSet,
            $this->collation,
            $this->allowDefault
        );
        
        $this->mockStringType = $this->createMock(
            'MilesAsylum\Schnoop\Schema\MySQL\DataType\StringTypeInterface'
        );
    }

    public function testSuccess()
    {
        $this->setMockStringTypeMethods(
            $this->mockStringType,
            $this->type,
            $this->length,
            $this->characterSet,
            $this->collation,
            $this->allowDefault
        );

        $this->assertTrue($this->constraint->matches($this->mockStringType));
    }

    public function testFailType()
    {
        $this->setMockStringTypeMethods(
            $this->mockStringType,
            null,
            $this->length,
            $this->characterSet,
            $this->collation,
            $this->allowDefault
        );

        $this->assertFalse($this->constraint->matches($this->mockStringType));
        $this->assertSame('string has correct type', $this->constraint->toString());
    }

    public function testFailLength()
    {
        $this->setMockStringTypeMethods(
            $this->mockStringType,
            $this->type,
            null,
            $this->characterSet,
            $this->collation,
            $this->allowDefault
        );
        
        $this->assertFalse($this->constraint->matches($this->mockStringType));
        $this->assertSame('string has correct length', $this->constraint->toString());
    }

    public function testFailCharacterSet()
    {
        $this->setMockStringTypeMethods(
            $this->mockStringType,
            $this->type,
            $this->length,
            null,
            $this->collation,
            $this->allowDefault
        );

        $this->assertFalse($this->constraint->matches($this->mockStringType));
        $this->assertSame('string has correct characterSet', $this->constraint->toString());
    }

    public function testFailCollation()
    {
        $this->setMockStringTypeMethods(
            $this->mockStringType,
            $this->type,
            $this->length,
            $this->characterSet,
            null,
            $this->allowDefault
        );

        $this->assertFalse($this->constraint->matches($this->mockStringType));
        $this->assertSame('string has correct collation', $this->constraint->toString());
    }

    public function testFailAllowDefault()
    {
        $this->setMockStringTypeMethods(
            $this->mockStringType,
            $this->type,
            $this->length,
            $this->characterSet,
            $this->collation,
            null
        );

        $this->assertFalse($this->constraint->matches($this->mockStringType));
        $this->assertSame('string has correct allowDefault', $this->constraint->toString());
    }

    /**
     * @param PHPUnit_Framework_MockObject_MockObject $mockStringType
     * @param $type
     * @param null|int $length
     * @param null|string $characterSet
     * @param null|string $collation
     * @param null|bool $allowDefault
     * @return StringTypeInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected function setMockStringTypeMethods(
        PHPUnit_Framework_MockObject_MockObject $mockStringType,
        $type,
        $length,
        $characterSet,
        $collation,
        $allowDefault
    ) {
        $mockStringType->expects($this->any())
            ->method('getType')
            ->willReturn($type);

        $mockStringType->expects($this->any())
            ->method('getLength')
            ->willReturn($length);

        $mockStringType->expects($this->any())
            ->method('getCharacterSet')
            ->willReturn($characterSet);

        $mockStringType->expects($this->any())
            ->method('getCollation')
            ->willReturn($collation);

        $mockStringType->expects($this->any())
            ->method('allowDefault')
            ->willReturn($allowDefault);
        
        return $mockStringType;
    }
}
