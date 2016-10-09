<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DataTypeFactoryInterface;
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

    protected function matchIntPattern($pattern, $typeStr)
    {
        $r = preg_match($pattern, $typeStr);

        if ($r === false) {
            throw new \RuntimeException('Error evaluating regular expression:' . preg_last_error());
        } elseif ($r === 1) {
            $r = preg_match('/\(\d+\)( unsigned| signed)?( zerofill)?$/i', $typeStr);
        }

        return (bool)$r;
    }

    /**
     * @param string $typeStr
     * @return int
     */
    protected function extractDisplayWidth($typeStr)
    {
        preg_match('/\((\d+)\)/', $typeStr, $matches);

        return (int)$matches[1];
    }

    /**
     * @param string $typeStr
     * @return bool
     */
    protected function extractSigned($typeStr)
    {
        return stripos($typeStr, ' unsigned') === false;
    }

    /**
     * @param string $typeStr
     * @return bool
     */
    protected function extractZeroFill($typeStr)
    {
        return stripos($typeStr, ' zerofill') !== false;
    }
}
