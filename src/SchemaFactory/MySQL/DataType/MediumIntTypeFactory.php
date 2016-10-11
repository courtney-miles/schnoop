<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\MediumIntType;

class MediumIntTypeFactory extends AbstractIntTypeFactory
{
    /**
     * {@inheritdoc}
     */
    public function newType()
    {
        return new MediumIntType();
    }

    /**
     * {@inheritdoc}
     */
    public function doRecognise($typeStr)
    {
        return $this->matchIntPattern('/^mediumint?/i', $typeStr);
    }
}
