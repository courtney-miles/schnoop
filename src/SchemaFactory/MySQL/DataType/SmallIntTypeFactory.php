<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\SmallIntType;

class SmallIntTypeFactory extends AbstractIntTypeFactory
{
    /**
     * {@inheritdoc}
     */
    public function newType()
    {
        return new SmallIntType();
    }

    /**
     * {@inheritdoc}
     */
    public function doRecognise($typeStr)
    {
        return $this->matchIntPattern('/^smallint?/i', $typeStr);
    }
}
