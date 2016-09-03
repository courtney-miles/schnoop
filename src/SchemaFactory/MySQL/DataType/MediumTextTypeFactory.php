<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\SchemaFactory\DataTypeFactoryInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\MediumTextType;

class MediumTextTypeFactory implements DataTypeFactoryInterface
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return MediumTextType|bool
     */
    public function create($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        $mediumTextType = new MediumTextType();
        $mediumTextType->setCollation($collation);

        return $mediumTextType;
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public function doRecognise($typeStr)
    {
        return preg_match('/^mediumtext$/i', $typeStr) === 1;
    }
}
