<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\Exception\ParametersParserException;

class ParametersParser
{
    /**
     * @var ParametersLexer
     */
    private $lexer;

    protected $tokenised = [];
    protected $pointer = 0;

    public function __construct(ParametersLexer $lexer)
    {
        $this->lexer = $lexer;
    }

    public function parse($parametersString)
    {
        $params = [];

        $countTokens = $this->tokenise($parametersString);

        if ($countTokens) {
            do {
                $params[] = $this->parseNextParameter();
                $this->clearWhitespace();
                $token = $this->nextToken();
            } while ($token[0] == ParametersLexer::T_COMMA);
        }

        return $params;
    }

    protected function parseNextParameter()
    {
        $direction = null;

        $this->clearWhitespace();

        if ($this->lookAhead()[0] == ParametersLexer::T_WORD
            && in_array(strtoupper($this->lookAhead()[1]), ['IN', 'OUT', 'INOUT'])
        ) {
            $direction = $this->nextToken()[1];
        }

        $this->clearWhitespace();

        $token = $this->nextToken();

        if ($token[0] == ParametersLexer::T_TICK) {
            $token = $this->nextToken();

            if ($token[0] !== ParametersLexer::T_WORD) {
                throw new ParametersParserException(
                    "A parameter name was expected but found '{$token[1]}' instead."
                );
            }

            $name = $token[1];
            $token = $this->nextToken();

            if ($token[0] !== ParametersLexer::T_TICK) {
                throw new ParametersParserException(
                    "Parameter name was expected to terminate with '`' but found '{$token[1]}' instead."
                );
            }
        } elseif ($token[0] == ParametersLexer::T_WORD) {
            $name = $token[1];
        } else {
            throw new ParametersParserException(
                "A parameter name was expected, but found '{$token[1]}' instead."
            );
        }

        $this->clearWhitespace();

        $token = $this->nextToken();

        if ($token[0] != ParametersLexer::T_WORD) {
            throw new ParametersParserException(
                "Data type was expected after the parameter name, but found '{$token[1]}' instead."
            );
        }

        $dataType = $token[1];

        $dataType .= $this->clearWhitespace();

        $token = $this->nextToken();

        if ($token[0] = ParametersLexer::T_BRACKET_OPEN) {
            $dataType .= $token[1];
            $dataType .= $this->readUntil(ParametersLexer::T_BRACKET_CLOSE);
        }

        $dataType .= $this->consume([ParametersLexer::T_WORD, ParametersLexer::T_WHITESPACE]);

        return [
            'direction' => $direction,
            'name' => $name,
            'dataType' => trim($dataType)
        ];
    }

    protected function tokenise($parameterString)
    {
        $this->tokenised = $this->lexer->tokenise(trim($parameterString));

        return count($this->tokenised);
    }

    protected function nextToken()
    {
        $token = null;

        if (isset($this->tokenised[$this->pointer])) {
            $token = $this->tokenised[$this->pointer];
            $this->pointer++;
        }

        return $token;
    }

    protected function lookAhead()
    {
        $token = null;

        if (isset($this->tokenised[$this->pointer])) {
            $token = $this->tokenised[$this->pointer];
        }

        return $token;
    }

    protected function clearWhitespace()
    {
        if ($this->lookAhead()[0] == ParametersLexer::T_WHITESPACE) {
            return $this->nextToken()[1];
        }

        return null;
    }

    protected function readUntil($tokenId, $inclusive = true)
    {
        $buffer = '';

        while ($this->lookAhead()[0] != $tokenId) {
            $buffer .= $this->nextToken()[1];
        }

        if ($inclusive) {
            $buffer .= $this->nextToken()[1];
        }

        return $buffer;
    }

    protected function consume(array $tokenIds)
    {
        $buffer = '';

        while (in_array($this->lookAhead()[0], $tokenIds)) {
            $buffer .= $this->nextToken()[1];
        }

        return $buffer;
    }
}
