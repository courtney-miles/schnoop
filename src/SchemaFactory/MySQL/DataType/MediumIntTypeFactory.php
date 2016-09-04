<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\MediumIntType;

class MediumIntTypeFactory extends AbstractIntTypeFactory
{
    public function newType()
    {
        return new MediumIntType();
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public function doRecognise($typeStr)
    {
        return $this->matchIntPattern('/^mediumint?/i', $typeStr);
    }
}
