<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\Exception\ParametersLexerException;

class ParametersLexer
{
    protected $terminals = [
        '/^(\()/' => self::T_BRACKET_OPEN,
        '/^(\))/' => self::T_BRACKET_CLOSE,
        '/^(\s+)/' => self::T_WHITESPACE,
        '/^(\w+)/' => self::T_WORD,
        '/^(,)/' => self::T_COMMA,
        '/([\"\'])(?:.*[^\\\\]+)*(?:(?:\\\\{2})*)+\1/xU' => self::T_ENCAPSED_STRING,
        '/^(`)/' => self::T_TICK
    ];

    protected $tokenNames = [
        self::T_BRACKET_OPEN => 'T_BRACKET_OPEN',
        self::T_BRACKET_CLOSE => 'T_BRACKET_CLOSE',
        self::T_WHITESPACE => 'T_WHITESPACE',
        self::T_WORD => 'T_WORD',
        self::T_COMMA => 'T_COMMA',
        self::T_ENCAPSED_STRING => 'T_ENCAPSED_STRING',
        self::T_TICK => 'T_TICK'
    ];

    const T_BRACKET_OPEN = 1;
    const T_BRACKET_CLOSE = 2;
    const T_WHITESPACE = 3;
    const T_WORD = 4;
    const T_COMMA = 5;
    const T_ENCAPSED_STRING = 6;
    const T_TICK = 7;

    public function tokenise($source)
    {
        $tokens = [];
        $offset = 0;

        while ($offset < strlen($source)) {
            $result = $this->match($source, $offset);

            if ($result === false) {
                throw new ParametersLexerException(
                    sprintf(
                        "There is an error in the syntax for the parameters string. Check the syntax near '%s'.",
                        substr($source, $offset, 10)
                    )
                );
            }

            $tokens[] = $result;
            $offset += strlen($result[1]);
        }

        return $tokens;
    }

    /**
     * @param int $token
     * @return string
     */
    public function getTokenName($token)
    {
        return $this->tokenNames[$token];
    }

    protected function match($source, $offset)
    {
        $source = substr($source, $offset);

        foreach ($this->terminals as $pattern => $name) {
            if (preg_match($pattern, $source, $matches)) {
                return [$name, $matches[0]];
            }
        }

        return false;
    }
}
