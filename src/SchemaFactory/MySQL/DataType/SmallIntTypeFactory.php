<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\SmallIntType;

class SmallIntTypeFactory extends AbstractIntTypeFactory
{
    public function newType()
    {
        return new SmallIntType();
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public function doRecognise($typeStr)
    {
        return $this->matchIntPattern('/^smallint?/i', $typeStr);
    }
}
