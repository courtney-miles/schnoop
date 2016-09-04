<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\SchemaFactory\DataTypeFactoryInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\VarCharType;

class VarCharTypeFactory extends AbstractCharTypeFactory
{
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        return $this->populate($this->newType(1), $typeStr, $collation);
    }

    public function populate(VarCharType $varCharType, $typeStr, $collation)
    {
        $varCharType->setLength($this->extractLength($typeStr));
        $varCharType->setCollation($collation);

        return $varCharType;
    }

    public function newType($length)
    {
        return new VarCharType($length);
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public function doRecognise($typeStr)
    {
        return preg_match('/^varchar\(\d+\)/i', $typeStr) === 1;
    }
}
