<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\Routine;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\ParametersLexer;

class ParametersLexerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ParametersLexer
     */
    protected $parametersLexer;

    public function setUp()
    {
        parent::setUp();

        $this->parametersLexer = new ParametersLexer();
    }

    public function testTokenise()
    {
        $params = "('Foo123',   `bar`)";

        $expectedTokens = [
            [
                ParametersLexer::T_BRACKET_OPEN,
                '('
            ],
            [
                ParametersLexer::T_ENCAPSED_STRING,
                "'Foo123'"
            ],
            [
                ParametersLexer::T_COMMA,
                ','
            ],
            [
                ParametersLexer::T_WHITESPACE,
                '   '
            ],
            [
                ParametersLexer::T_TICK,
                '`'
            ],
            [
                ParametersLexer::T_WORD,
                'bar'
            ],
            [
                ParametersLexer::T_TICK,
                '`'
            ],
            [
                ParametersLexer::T_BRACKET_CLOSE,
                ')'
            ]
        ];

        $actualTokens = $this->parametersLexer->tokenise($params);

        $this->assertSame($expectedTokens, $actualTokens);
    }
}
