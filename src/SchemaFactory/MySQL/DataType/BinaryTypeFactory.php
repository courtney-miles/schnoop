<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/07/16
 * Time: 6:19 PM
 */

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\BinaryType;

class BinaryTypeFactory extends AbstractCharTypeFactory
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return BinaryType|bool
     */
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        return $this->populate($this->newType(), $typeStr);
    }

    public function populate(BinaryType $binaryType, $typeStr)
    {
        $binaryType->setLength($this->extractLength($typeStr));

        return $binaryType;
    }

    public function newType()
    {
        return new BinaryType();
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public function doRecognise($typeStr)
    {
        return preg_match('/^binary\(\d+\)/i', $typeStr) === 1;
    }
}
