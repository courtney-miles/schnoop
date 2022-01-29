<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\VarCharType;

class VarCharTypeFactory extends AbstractCharTypeFactory
{
    /**
     * {@inheritdoc}
     */
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        return $this->populate($this->newType(1), $typeStr, $collation);
    }

    /**
     * Populate the properties of the supplied varchar type from the type string.
     *
     * @param string $typeStr
     * @param string $collation
     *
     * @return VarCharType
     */
    public function populate(VarCharType $varCharType, $typeStr, $collation)
    {
        $varCharType->setLength($this->extractLength($typeStr));
        $varCharType->setCollation($collation);

        return $varCharType;
    }

    /**
     * Create a new VarChar type object.
     *
     * @param int $length Character length
     *
     * @return VarCharType
     */
    public function newType($length)
    {
        return new VarCharType($length);
    }

    /**
     * {@inheritdoc}
     */
    public function doRecognise($typeStr)
    {
        return 1 === preg_match('/^varchar\(\d+\)/i', $typeStr);
    }
}
