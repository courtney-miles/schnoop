<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\IntType;

class IntTypeFactory extends AbstractIntTypeFactory
{
    /**
     * {@inheritdoc}
     */
    public function newType()
    {
        return new IntType();
    }

    /**
     * {@inheritdoc}
     */
    public function doRecognise($typeStr)
    {
        return $this->matchIntPattern('/^int(eger)?/i', $typeStr);
    }
}
