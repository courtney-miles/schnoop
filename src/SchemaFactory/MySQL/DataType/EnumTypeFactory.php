<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\EnumType;

class EnumTypeFactory extends AbstractOptionsTypeFactory
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return DataTypeInterface|bool
     */
    public function create($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        $enumType = new EnumType();
        $enumType->setOptions($this->getOptions($typeStr));
        $enumType->setCollation($collation);

        return $enumType;
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public function doRecognise($typeStr)
    {
        return preg_match('/^enum\(.+\)$/i', $typeStr) === 1;
    }
}
