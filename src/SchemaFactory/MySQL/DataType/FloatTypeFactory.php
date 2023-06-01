<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\FloatType;

class FloatTypeFactory extends AbstractNumericPointTypeFactory
{
    public function newType()
    {
        return new FloatType();
    }

    public function doRecognise($typeStr)
    {
        return $this->matchPointPattern('/^float/i', $typeStr);
    }
}
