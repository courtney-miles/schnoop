<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\DecimalType;

class DecimalTypeFactory extends AbstractNumericPointTypeFactory
{

    public function newType()
    {
        return new DecimalType();
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public function doRecognise($typeStr)
    {
        return $this->matchPointPattern('/^decimal/i', $typeStr);
    }
}
