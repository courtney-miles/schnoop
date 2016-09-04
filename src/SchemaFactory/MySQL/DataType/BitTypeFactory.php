<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\BitType;

class BitTypeFactory extends AbstractCharTypeFactory
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return BitType|bool
     */
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        return $this->populate($this->newType(), $typeStr);
    }

    public function populate(BitType $bitType, $typeStr)
    {
        $bitType->setLength($this->extractLength($typeStr));

        return $bitType;
    }

    public function newType()
    {
        return new BitType();
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public function doRecognise($typeStr)
    {
        return preg_match('/^bit\(\d+\)/i', $typeStr) === 1;
    }
}
