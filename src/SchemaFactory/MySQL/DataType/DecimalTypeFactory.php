<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\DecimalType;

class DecimalTypeFactory extends AbstractNumericPointTypeFactory
{
    /**
     * {@inheritdoc}
     */
    public function newType()
    {
        return new DecimalType();
    }

    /**
     * {@inheritdoc}
     */
    public function doRecognise($typeStr)
    {
        return $this->matchPointPattern('/^decimal/i', $typeStr);
    }
}
