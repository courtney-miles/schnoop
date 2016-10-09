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

    /**
     * @dataProvider getTokenNameTestData
     * @param $const
     * @param $expectedName
     */
    public function testGetTokenName($const, $expectedName)
    {
        $this->assertSame($expectedName, $this->parametersLexer->getTokenName($const));
    }

    public function getTokenNameTestData()
    {
        return [
            [ParametersLexer::T_BRACKET_OPEN, 'T_BRACKET_OPEN'],
            [ParametersLexer::T_BRACKET_CLOSE, 'T_BRACKET_CLOSE'],
            [ParametersLexer::T_WHITESPACE, 'T_WHITESPACE'],
            [ParametersLexer::T_WORD, 'T_WORD'],
            [ParametersLexer::T_COMMA, 'T_COMMA'],
            [ParametersLexer::T_ENCAPSED_STRING, 'T_ENCAPSED_STRING'],
            [ParametersLexer::T_TICK, 'T_TICK'],
        ];
    }

    /**
     * @expectedException \MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\Exception\ParametersLexerException
     * @expectedExceptionMessage There is an error in the syntax for the parameters string. Check the syntax near '$ `bar`)'.
     */
    public function testExceptionOnInvalidString()
    {
        $invalidString = "('Foo123',  $ `bar`)";

        $this->parametersLexer->tokenise($invalidString);
    }
}
