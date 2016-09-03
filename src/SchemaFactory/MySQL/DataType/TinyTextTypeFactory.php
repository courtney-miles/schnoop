<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\TinyTextType;

class TinyTextTypeFactory
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return TinyTextType|bool
     */
    public function create($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        $tinyTextType = new TinyTextType();
        $tinyTextType->setCollation($collation);

        return $tinyTextType;
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public function doRecognise($typeStr)
    {
        return preg_match('/^tinytext$/i', $typeStr) === 1;
    }
}