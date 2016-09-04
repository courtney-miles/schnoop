<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\VarBinaryType;

class VarBinaryTypeFactory extends AbstractCharTypeFactory
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return VarBinaryType|bool
     */
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        return $this->newType($this->extractLength($typeStr));
    }

    public function newType($length)
    {
        return new VarBinaryType($length);
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public function doRecognise($typeStr)
    {
        return preg_match('/^varbinary\(\d+\)/i', $typeStr) === 1;
    }
}
