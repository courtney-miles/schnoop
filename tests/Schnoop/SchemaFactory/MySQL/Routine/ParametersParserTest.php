<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\Routine;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\Exception\ParametersParserException;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\ParametersLexer;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\ParametersParser;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ParametersParserTest extends TestCase
{
    /**
     * @var ParametersParser
     */
    protected $parameterParser;

    /**
     * @var ParametersLexer|MockObject
     */
    protected $mockLexer;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockLexer = $this->createMock(ParametersLexer::class);

        $this->parameterParser = new ParametersParser($this->mockLexer);
    }

    public function testNoParameters()
    {
        $parameterStr = '';
        $tokens = [];

        $this->mockLexer->method('tokenise')
            ->with(trim($parameterStr))
            ->willReturn($tokens);

        $this->assertSame([], $this->parameterParser->parse($parameterStr));
    }

    public function testSingleParameter()
    {
        $parameterStr = 'foo varchar(22)';
        $tokens = [
            [ParametersLexer::T_WORD, 'foo'],
            [ParametersLexer::T_WORD, 'varchar'],
            [ParametersLexer::T_BRACKET_OPEN, '('],
            [ParametersLexer::T_WORD, '22'],
            [ParametersLexer::T_BRACKET_CLOSE, ')'],
        ];

        $this->mockLexer->method('tokenise')
            ->with(trim($parameterStr))
            ->willReturn($tokens);

        $this->assertSame(
            [
                [
                    'direction' => null,
                    'name' => 'foo',
                    'dataType' => 'varchar(22)',
                ],
            ],
            $this->parameterParser->parse($parameterStr)
        );
    }

    public function testDirectionParameter()
    {
        $parameterStr = 'INOUT foo varchar(22)';
        $tokens = [
            [ParametersLexer::T_WORD, 'INOUT'],
            [ParametersLexer::T_WORD, 'foo'],
            [ParametersLexer::T_WORD, 'varchar'],
            [ParametersLexer::T_BRACKET_OPEN, '('],
            [ParametersLexer::T_WORD, '22'],
            [ParametersLexer::T_BRACKET_CLOSE, ')'],
        ];

        $this->mockLexer->method('tokenise')
            ->with(trim($parameterStr))
            ->willReturn($tokens);

        $this->assertSame(
            [
                [
                    'direction' => 'INOUT',
                    'name' => 'foo',
                    'dataType' => 'varchar(22)',
                ],
            ],
            $this->parameterParser->parse($parameterStr)
        );
    }

    public function testQuotedParameter()
    {
        $parameterStr = '`foo` varchar(22)';
        $tokens = [
            [ParametersLexer::T_TICK, '`'],
            [ParametersLexer::T_WORD, 'foo'],
            [ParametersLexer::T_TICK, '`'],
            [ParametersLexer::T_WORD, 'varchar'],
            [ParametersLexer::T_BRACKET_OPEN, '('],
            [ParametersLexer::T_WORD, '22'],
            [ParametersLexer::T_BRACKET_CLOSE, ')'],
        ];

        $this->mockLexer->method('tokenise')
            ->with(trim($parameterStr))
            ->willReturn($tokens);

        $this->assertSame(
            [
                [
                    'direction' => null,
                    'name' => 'foo',
                    'dataType' => 'varchar(22)',
                ],
            ],
            $this->parameterParser->parse($parameterStr)
        );
    }

    public function testMultipleParameters()
    {
        $parameterStr = '`foo` varchar(22),bar int(10)';
        $tokens = [
            [ParametersLexer::T_TICK, '`'],
            [ParametersLexer::T_WORD, 'foo'],
            [ParametersLexer::T_TICK, '`'],
            [ParametersLexer::T_WORD, 'varchar'],
            [ParametersLexer::T_BRACKET_OPEN, '('],
            [ParametersLexer::T_WORD, '22'],
            [ParametersLexer::T_BRACKET_CLOSE, ')'],
            [ParametersLexer::T_COMMA, ','],
            [ParametersLexer::T_WORD, 'bar'],
            [ParametersLexer::T_WORD, 'int'],
            [ParametersLexer::T_BRACKET_OPEN, '('],
            [ParametersLexer::T_WORD, '10'],
            [ParametersLexer::T_BRACKET_CLOSE, ')'],
        ];

        $this->mockLexer->method('tokenise')
            ->with(trim($parameterStr))
            ->willReturn($tokens);

        $this->assertSame(
            [
                [
                    'direction' => null,
                    'name' => 'foo',
                    'dataType' => 'varchar(22)',
                ],
                [
                    'direction' => null,
                    'name' => 'bar',
                    'dataType' => 'int(10)',
                ],
            ],
            $this->parameterParser->parse($parameterStr)
        );
    }

    public function testComplexParameters()
    {
        $parameterStr = "  IN `foo` INT (8) UNSIGNED ZEROFILL , bar ENUM('abc' , '123')  ";
        $tokens = [
            [ParametersLexer::T_WORD, 'IN'],
            [ParametersLexer::T_WHITESPACE, ' '],
            [ParametersLexer::T_TICK, '`'],
            [ParametersLexer::T_WORD, 'foo'],
            [ParametersLexer::T_TICK, '`'],
            [ParametersLexer::T_WHITESPACE, ' '],
            [ParametersLexer::T_WORD, 'INT'],
            [ParametersLexer::T_WHITESPACE, ' '],
            [ParametersLexer::T_BRACKET_OPEN, '('],
            [ParametersLexer::T_WORD, '8'],
            [ParametersLexer::T_BRACKET_CLOSE, ')'],
            [ParametersLexer::T_WHITESPACE, ' '],
            [ParametersLexer::T_WORD, 'UNSIGNED'],
            [ParametersLexer::T_WHITESPACE, ' '],
            [ParametersLexer::T_WORD, 'ZEROFILL'],
            [ParametersLexer::T_WHITESPACE, ' '],
            [ParametersLexer::T_COMMA, ','],
            [ParametersLexer::T_WHITESPACE, ' '],
            [ParametersLexer::T_WORD, 'bar'],
            [ParametersLexer::T_WHITESPACE, ' '],
            [ParametersLexer::T_WORD, 'ENUM'],
            [ParametersLexer::T_BRACKET_OPEN, '('],
            [ParametersLexer::T_ENCAPSED_STRING, "'abc'"],
            [ParametersLexer::T_WHITESPACE, ' '],
            [ParametersLexer::T_COMMA, ','],
            [ParametersLexer::T_WHITESPACE, ' '],
            [ParametersLexer::T_ENCAPSED_STRING, "'123'"],
            [ParametersLexer::T_BRACKET_CLOSE, ')'],
        ];

        $this->mockLexer->method('tokenise')
            ->with(trim($parameterStr))
            ->willReturn($tokens);

        $this->assertSame(
            [
                [
                    'direction' => 'IN',
                    'name' => 'foo',
                    'dataType' => 'INT (8) UNSIGNED ZEROFILL',
                ],
                [
                    'direction' => null,
                    'name' => 'bar',
                    'dataType' => "ENUM('abc' , '123')",
                ],
            ],
            $this->parameterParser->parse($parameterStr)
        );
    }

    public function testUnexpectedTokenAfterTick()
    {
        $this->expectExceptionMessage("A parameter name was expected but found '`' instead.");
        $this->expectException(ParametersParserException::class);

        $parameterStr = '``';
        $tokens = [
            [ParametersLexer::T_TICK, '`'],
            [ParametersLexer::T_TICK, '`'],
        ];

        $this->mockLexer->method('tokenise')
            ->with(trim($parameterStr))
            ->willReturn($tokens);

        $this->parameterParser->parse($parameterStr);
    }

    public function testUnexpectedTokenBeforeTick()
    {
        $this->expectExceptionMessage("Parameter name was expected to terminate with '`' but found ',' instead.");
        $this->expectException(ParametersParserException::class);
        $parameterStr = '`foo,`';
        $tokens = [
            [ParametersLexer::T_TICK, '`'],
            [ParametersLexer::T_WORD, 'foo'],
            [ParametersLexer::T_COMMA, ','],
            [ParametersLexer::T_TICK, '`'],
        ];

        $this->mockLexer->method('tokenise')
            ->with(trim($parameterStr))
            ->willReturn($tokens);

        $this->parameterParser->parse($parameterStr);
    }

    public function testUnexpectedTokenBeforeParameterName()
    {
        $this->expectExceptionMessage("A parameter name was expected, but found ',' instead.");
        $this->expectException(ParametersParserException::class);
        $parameterStr = ',`foo`';
        $tokens = [
            [ParametersLexer::T_COMMA, ','],
            [ParametersLexer::T_TICK, '`'],
            [ParametersLexer::T_WORD, 'foo'],
            [ParametersLexer::T_TICK, '`'],
        ];

        $this->mockLexer->method('tokenise')
            ->with(trim($parameterStr))
            ->willReturn($tokens);

        $this->parameterParser->parse($parameterStr);
    }

    public function testUnexpectedTokenAfterParameterName()
    {
        $this->expectExceptionMessage("Data type was expected after the parameter name, but found ',' instead.");
        $this->expectException(ParametersParserException::class);
        $parameterStr = '`foo`,';
        $tokens = [
            [ParametersLexer::T_TICK, '`'],
            [ParametersLexer::T_WORD, 'foo'],
            [ParametersLexer::T_TICK, '`'],
            [ParametersLexer::T_COMMA, ','],
        ];

        $this->mockLexer->method('tokenise')
            ->with(trim($parameterStr))
            ->willReturn($tokens);

        $this->parameterParser->parse($parameterStr);
    }
}
