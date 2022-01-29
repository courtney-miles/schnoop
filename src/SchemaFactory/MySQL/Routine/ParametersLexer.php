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
        '/^(`)/' => self::T_TICK,
    ];

    protected $tokenNames = [
        self::T_BRACKET_OPEN => 'T_BRACKET_OPEN',
        self::T_BRACKET_CLOSE => 'T_BRACKET_CLOSE',
        self::T_WHITESPACE => 'T_WHITESPACE',
        self::T_WORD => 'T_WORD',
        self::T_COMMA => 'T_COMMA',
        self::T_ENCAPSED_STRING => 'T_ENCAPSED_STRING',
        self::T_TICK => 'T_TICK',
    ];

    public const T_BRACKET_OPEN = 1;
    public const T_BRACKET_CLOSE = 2;
    public const T_WHITESPACE = 3;
    public const T_WORD = 4;
    public const T_COMMA = 5;
    public const T_ENCAPSED_STRING = 6;
    public const T_TICK = 7;

    /**
     * Tokenise a parameter string.
     *
     * @param string $paramString Parameter string
     *
     * @return array tokens
     *
     * @throws ParametersLexerException
     */
    public function tokenise($paramString)
    {
        $tokens = [];
        $offset = 0;

        while ($offset < strlen($paramString)) {
            $result = $this->match($paramString, $offset);

            if (false === $result) {
                throw new ParametersLexerException(sprintf("There is an error in the syntax for the parameters string. Check the syntax near '%s'.", substr($paramString, $offset, 10)));
            }

            $tokens[] = $result;
            $offset += strlen($result[1]);
        }

        return $tokens;
    }

    /**
     * Get the name of the supplied token.
     *
     * @param int $token one of self::T_* constants
     *
     * @return string
     */
    public function getTokenName($token)
    {
        return $this->tokenNames[$token];
    }

    /**
     * Returns the first match of terminal pattern.
     *
     * @param $source
     * @param $offset
     *
     * @return array|bool First array element is the matching token name, and
     *                    the second is the matching value.  False if there was no match.
     */
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
