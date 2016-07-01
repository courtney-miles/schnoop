<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 24/06/16
 * Time: 7:37 AM
 */

namespace MilesAsylum\Schnoop\Tests\PHPUnit\Framework\Constraint;

use MilesAsylum\Schnoop\PHPUnit\Framework\Constraint\IsColumnConstruct;
use MilesAsylum\Schnoop\Schema\MySQL\Column\ColumnInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class IsColumnConstructTest extends TestCase
{
    /**
     * @var IsColumnConstruct
     */
    protected $isColumnConstruct;

    /**
     * @var ColumnInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockColumn;

    /**
     * @var string
     */
    protected $name = 'schnoop_col';

    /**
     * @var DataTypeInterface
     */
    protected $dataType;

    /**
     * @var bool
     */
    protected $allowNull = true;

    /**
     * @var bool
     */
    protected $hasDefault = true;

    /**
     * @var string
     */
    protected $default = '123';

    /**
     * @var string
     */
    protected $comment = 'Schnoop comment';

    public function setUp()
    {
        parent::setUp();

        $this->dataType = $this->createMock('MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface');

        $this->isColumnConstruct = new IsColumnConstruct(
            $this->name,
            $this->dataType,
            $this->allowNull,
            $this->hasDefault,
            $this->default,
            $this->comment
        );

        $this->mockColumn = $this->createMock('MilesAsylum\Schnoop\Schema\MySQL\Column\ColumnInterface');
    }

    public function testSuccessWithDataType()
    {
        $this->setMockColumnMethods(
            $this->mockColumn,
            $this->name,
            $this->dataType,
            $this->allowNull,
            $this->hasDefault,
            $this->default,
            $this->comment
        );

        $this->assertTrue($this->isColumnConstruct->matches($this->mockColumn));
    }

    public function testSuccessWithInstanceOfDataType()
    {
        $isColumnConstruct = new IsColumnConstruct(
            $this->name,
            'MilesAsylum\Schnoop\Schema\MySQL\Column\ColumnInterface',
            $this->allowNull,
            $this->hasDefault,
            $this->default,
            $this->comment
        );

        $this->setMockColumnMethods(
            $this->mockColumn,
            $this->name,
            $this->dataType,
            $this->allowNull,
            $this->hasDefault,
            $this->default,
            $this->comment
        );

        $this->assertTrue($isColumnConstruct->matches($this->mockColumn));
    }

    public function testFailName()
    {
        $this->setMockColumnMethods(
            $this->mockColumn,
            null,
            $this->dataType,
            $this->allowNull,
            $this->hasDefault,
            $this->default,
            $this->comment
        );
        
        $this->assertFalse($this->isColumnConstruct->matches($this->mockColumn));
        $this->assertSame('column has correct name', $this->isColumnConstruct->toString());
    }

    public function testFailDataType()
    {
        $this->setMockColumnMethods(
            $this->mockColumn,
            $this->name,
            null,
            $this->allowNull,
            $this->hasDefault,
            $this->default,
            $this->comment
        );

        $this->assertFalse($this->isColumnConstruct->matches($this->mockColumn));
        $this->assertSame('column has correct dataType', $this->isColumnConstruct->toString());
    }

    public function testFailInstanceOfDataType()
    {
        $isColumnConstruct = new IsColumnConstruct(
            $this->name,
            '\Wrong',
            $this->allowNull,
            $this->hasDefault,
            $this->default,
            $this->comment
        );

        $this->setMockColumnMethods(
            $this->mockColumn,
            $this->name,
            $this->dataType,
            $this->allowNull,
            $this->hasDefault,
            $this->default,
            $this->comment
        );

        $this->assertFalse($isColumnConstruct->matches($this->mockColumn));
        $this->assertSame('column has correct instance for dataType', $isColumnConstruct->toString());
    }

    public function testFailAllowNull()
    {
        $this->setMockColumnMethods(
            $this->mockColumn,
            $this->name,
            $this->dataType,
            null,
            $this->hasDefault,
            $this->default,
            $this->comment
        );

        $this->assertFalse($this->isColumnConstruct->matches($this->mockColumn));
        $this->assertSame('column has correct allowNull', $this->isColumnConstruct->toString());
    }

    public function testFailHasDefault()
    {
        $this->setMockColumnMethods(
            $this->mockColumn,
            $this->name,
            $this->dataType,
            $this->allowNull,
            null,
            $this->default,
            $this->comment
        );

        $this->assertFalse($this->isColumnConstruct->matches($this->mockColumn));
        $this->assertSame('column correctly identifies hasDefault', $this->isColumnConstruct->toString());
    }
    
    public function testFailDefault()
    {
        $this->setMockColumnMethods(
            $this->mockColumn,
            $this->name,
            $this->dataType,
            $this->allowNull,
            $this->hasDefault,
            null,
            $this->comment
        );

        $this->assertFalse($this->isColumnConstruct->matches($this->mockColumn));
        $this->assertSame('column has correct default', $this->isColumnConstruct->toString());
    }

    public function testFailComment()
    {
        $this->setMockColumnMethods(
            $this->mockColumn,
            $this->name,
            $this->dataType,
            $this->allowNull,
            $this->hasDefault,
            $this->default,
            null
        );

        $this->assertFalse($this->isColumnConstruct->matches($this->mockColumn));
        $this->assertSame('column has correct comment', $this->isColumnConstruct->toString());
    }

    /**
     * @param PHPUnit_Framework_MockObject_MockObject $mockColumn
     * @param string|null $name
     * @param DataTypeInterface|null $dataType
     * @param bool|null $allowNull
     * @param bool $hasDefault
     * @param string|null $default
     * @param string|null $comment
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function setMockColumnMethods(
        PHPUnit_Framework_MockObject_MockObject $mockColumn,
        $name,
        $dataType,
        $allowNull,
        $hasDefault,
        $default,
        $comment
    ) {
        $mockColumn->method('getName')
            ->willReturn($name);

        $mockColumn->method('getDataType')
            ->willReturn($dataType);

        $mockColumn->method('isAllowNull')
            ->willReturn($allowNull);

        $mockColumn->method('hasDefault')
            ->willReturn($hasDefault);
        
        $mockColumn->method('getDefault')
            ->willReturn($default);

        $mockColumn->method('getComment')
            ->willReturn($comment);

        return $mockColumn;
    }
}
