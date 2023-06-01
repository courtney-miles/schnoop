<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\BigIntType;

class BigIntTypeFactory extends AbstractIntTypeFactory
{
    public function doRecognise($typeStr)
    {
        return $this->matchIntPattern('/^bigint?/i', $typeStr);
    }

    public function newType()
    {
        return new BigIntType();
    }
}
