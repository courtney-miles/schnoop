<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\VarBinaryType;

class VarBinaryTypeFactory extends AbstractCharTypeFactory
{
    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function doRecognise($typeStr)
    {
        return preg_match('/^varbinary\(\d+\)/i', $typeStr) === 1;
    }
}
