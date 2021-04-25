<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\Exception\ParametersParserException;

class ParametersParser
{
    /**
     * @var ParametersLexer
     */
    private $lexer;

    /**
     * Tokenised parameter string.
     * @var array
     */
    protected $tokenised = [];

    /**
     * Pointer for the parameter string.
     * @var int
     */
    protected $pointer = 0;

    /**
     * ParametersParser constructor.
     * @param ParametersLexer $lexer
     */
    public function __construct(ParametersLexer $lexer)
    {
        $this->lexer = $lexer;
    }

    /**
     * Parse the supplied parameter string to it's tokens.
     * @param $parametersString
     * @return array Tokens
     */
    public function parse($parametersString)
    {
        $params = [];

        $countTokens = $this->tokenise($parametersString);

        if ($countTokens) {
            do {
                $params[] = $this->parseNextParameter();
                $this->clearWhitespace();
                $token = $this->nextToken();
            } while ($token !== null && $token[0] == ParametersLexer::T_COMMA);
        }

        return $params;
    }

    /**
     * Parse the next parameter from the string, leaving the pointer at the next parameter.
     * @return array
     * @throws ParametersParserException
     */
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

    /**
     * Tokenise the parameters string.
     * @param $parametersString
     * @return int
     */
    protected function tokenise($parametersString)
    {
        $this->tokenised = $this->lexer->tokenise(trim($parametersString));

        return count($this->tokenised);
    }

    /**
     * Get the next token for the parameters string.
     * @return array|null Token.
     */
    protected function nextToken()
    {
        $token = null;

        if (isset($this->tokenised[$this->pointer])) {
            $token = $this->tokenised[$this->pointer];
            $this->pointer++;
        }

        return $token;
    }

    /**
     * Get the next token without moving the the pointer.
     * @return array|null Token.
     */
    protected function lookAhead()
    {
        $token = null;

        if (isset($this->tokenised[$this->pointer])) {
            $token = $this->tokenised[$this->pointer];
        }

        return $token;
    }

    /**
     * Move the pointer past all white space, and return the next token.
     * @return array|null Token
     */
    protected function clearWhitespace()
    {
        if ($this->lookAhead() === null) {
            return null;
        }

        if ($this->lookAhead()[0] == ParametersLexer::T_WHITESPACE) {
            return $this->nextToken()[1];
        }

        return null;
    }

    /**
     * Read all tokens until we reach an instance of the specified token.
     * @param int $tokenId The token to stop at.
     * @param bool $inclusive Set to true to include the specified token in
     * the returned results. Otherwise the pointer will be positioned at the
     * token ready for the next read.
     * @return string
     */
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

    /**
     * Consume all tokens that until one is found that is not included in the supplied array of token IDs.
     * @param array $tokenIds
     * @return string
     */
    protected function consume(array $tokenIds)
    {
        $buffer = '';

        if ($this->lookAhead() === null) {
            return $buffer;
        }

        while (in_array($this->lookAhead()[0], $tokenIds)) {
            $buffer .= $this->nextToken()[1];
        }

        return $buffer;
    }
}
