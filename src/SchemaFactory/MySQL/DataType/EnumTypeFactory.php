<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\EnumType;

class EnumTypeFactory extends AbstractOptionsTypeFactory
{
    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function doRecognise($typeStr)
    {
        return preg_match('/^enum\(.+\)$/i', $typeStr) === 1;
    }
}
