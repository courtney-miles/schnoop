<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\SetType;

class SetTypeFactory extends AbstractOptionsTypeFactory
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

        $setType = new SetType();
        $setType->setOptions($this->getOptions($typeStr));
        $setType->setCollation($collation);

        return $setType;
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public function doRecognise($typeStr)
    {
        return preg_match('/^set\(.+\)$/i', $typeStr) === 1;
    }
}
