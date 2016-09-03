<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\TinyIntType;

class TinyIntTypeFactory extends AbstractIntTypeFactory
{
    public function newType()
    {
        return new TinyIntType();
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public function doRecognise($typeStr)
    {
        return $this->matchIntPattern('/^tinyint/i', $typeStr);
    }
}
