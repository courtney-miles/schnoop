<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\FloatType;

class FloatTypeFactory extends AbstractNumericPointTypeFactory
{
    /**
     * {@inheritdoc}
     */
    public function newType()
    {
        return new FloatType();
    }

    /**
     * {@inheritdoc}
     */
    public function doRecognise($typeStr)
    {
        return $this->matchPointPattern('/^float/i', $typeStr);
    }
}
