<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\DoubleType;

class DoubleTypeFactory extends AbstractNumericPointTypeFactory
{
    public function newType()
    {
        return new DoubleType();
    }

    public function doRecognise($typeStr)
    {
        return $this->matchPointPattern('/^double/i', $typeStr);
    }
}
