<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\DecimalType;

class DecimalTypeFactory extends AbstractNumericPointTypeFactory
{
    public function newType()
    {
        return new DecimalType();
    }

    public function doRecognise($typeStr)
    {
        return $this->matchPointPattern('/^decimal/i', $typeStr);
    }
}
