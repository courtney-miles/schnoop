<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\BitType;

class BitTypeFactory extends AbstractCharTypeFactory
{
    /**
     * {@inheritdoc}
     */
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        return $this->populate($this->newType(), $typeStr);
    }

    /**
     * Populate the properties of the supplied bit type from the type string
     * @param BitType $bitType
     * @param string $typeStr
     * @return BitType
     */
    public function populate(BitType $bitType, $typeStr)
    {
        $bitType->setLength($this->extractLength($typeStr));

        return $bitType;
    }

    /**
     * Create a new Bit type object.
     * @return BitType
     */
    public function newType()
    {
        return new BitType();
    }

    /**
     * {@inheritdoc}
     */
    public function doRecognise($typeStr)
    {
        return preg_match('/^bit\(\d+\)/i', $typeStr) === 1;
    }
}
