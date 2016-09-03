<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\SchemaFactory\DataTypeFactoryInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\TextType;

class TextTypeFactory implements DataTypeFactoryInterface
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return TextType|bool
     */
    public function create($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        $textType = new TextType();
        $textType->setCollation($collation);

        return $textType;
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public function doRecognise($typeStr)
    {
        return preg_match('/^text$/i', $typeStr) === 1;
    }
}
