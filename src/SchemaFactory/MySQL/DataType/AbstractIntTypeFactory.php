<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\IntTypeInterface;

abstract class AbstractIntTypeFactory implements IntTypeFactoryInterface
{
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        return $this->populate($this->newType(), $typeStr);
    }

    public function populate(IntTypeInterface $intType, $typeStr)
    {
        $intType->setSigned($this->extractSigned($typeStr));
        $intType->setDisplayWidth($this->extractDisplayWidth($typeStr));
        $intType->setZeroFill($this->extractZeroFill($typeStr));

        return $intType;
    }

    /**
     * Checks the integer type string against the supplied pattern and that it confirms to an expected structure.
     *
     * @param string $pattern I.e. /^int|integer/i or /^bigint/i
     * @param string $typeStr
     *
     * @return bool true if the type string matches the supplied pattern and conforms to an expected structure
     */
    protected function matchIntPattern($pattern, $typeStr)
    {
        $r = preg_match($pattern, $typeStr);

        if (false === $r) {
            throw new \RuntimeException('Error evaluating regular expression:'.preg_last_error());
        } elseif (1 === $r) {
            $r = preg_match('/[\(\d+\)]?( unsigned| signed)?( zerofill)?$/i', $typeStr);
        }

        return (bool) $r;
    }

    /**
     * Extract the display width from an integer type string.
     *
     * @param string $typeStr
     *
     * @return int|null display width
     */
    protected function extractDisplayWidth($typeStr)
    {
        preg_match('/\((\d+)\)/', $typeStr, $matches);

        return isset($matches[1]) ? (int) $matches[1] : null;
    }

    /**
     * Extract if the type string is for an signed integer.
     *
     * @param string $typeStr
     *
     * @return bool true if the type string specifies the integer is signed
     */
    protected function extractSigned($typeStr)
    {
        return false === stripos($typeStr, ' unsigned');
    }

    /**
     * Extract if the type string is for a zero-filled integer.
     *
     * @param string $typeStr
     *
     * @return bool true if the type string specifies zero-filling
     */
    protected function extractZeroFill($typeStr)
    {
        return false !== stripos($typeStr, ' zerofill');
    }
}
