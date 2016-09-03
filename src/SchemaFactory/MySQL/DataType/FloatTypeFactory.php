<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\FloatType;

class FloatTypeFactory extends AbstractNumericPointTypeFactory
{
    public function newType()
    {
        return new FloatType();
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public function doRecognise($typeStr)
    {
        return $this->matchPointPattern('/^float/i', $typeStr);
    }
}
