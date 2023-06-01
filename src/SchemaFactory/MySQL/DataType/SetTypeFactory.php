<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\SetType;

class SetTypeFactory extends AbstractOptionsTypeFactory
{
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        $setType = new SetType();
        $setType->setOptions($this->extractOptions($typeStr));
        $setType->setCollation($collation);

        return $setType;
    }

    public function doRecognise($typeStr)
    {
        return 1 === preg_match('/^set\(.+\)$/i', $typeStr);
    }
}
