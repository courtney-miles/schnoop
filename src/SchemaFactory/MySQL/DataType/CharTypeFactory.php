<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\CharType;

class CharTypeFactory extends AbstractCharTypeFactory
{
    public function create($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        return $this->populate($this->newType(), $typeStr, $collation);
    }

    public function populate(CharType $charType, $typeStr, $collation)
    {
        $charType->setLength($this->extractLength($typeStr));
        $charType->setCollation($collation);

        return $charType;
    }

    public function newType()
    {
        return new CharType();
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public function doRecognise($typeStr)
    {
        return preg_match('/^char\(\d+\)/i', $typeStr) === 1;
    }
}
