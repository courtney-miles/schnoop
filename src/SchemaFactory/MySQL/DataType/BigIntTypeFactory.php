<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\BigIntType;

class BigIntTypeFactory extends AbstractIntTypeFactory
{
    /**
     * {@inheritdoc}
     */
    public function doRecognise($typeStr)
    {
        return $this->matchIntPattern('/^bigint?/i', $typeStr);
    }

    /**
     * {@inheritdoc}
     */
    public function newType()
    {
        return new BigIntType();
    }
}
