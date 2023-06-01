<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\EnumType;

class EnumTypeFactory extends AbstractOptionsTypeFactory
{
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        $enumType = new EnumType();
        $enumType->setOptions($this->extractOptions($typeStr));
        $enumType->setCollation($collation);

        return $enumType;
    }

    public function doRecognise($typeStr)
    {
        return 1 === preg_match('/^enum\(.+\)$/i', $typeStr);
    }
}
