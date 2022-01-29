<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\CharType;

class CharTypeFactory extends AbstractCharTypeFactory
{
    /**
     * {@inheritdoc}
     */
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        return $this->populate($this->newType(), $typeStr, $collation);
    }

    /**
     * Populate the properties of supplied character type from the type string.
     *
     * @param string $typeStr
     * @param string $collation
     *
     * @return CharType
     */
    public function populate(CharType $charType, $typeStr, $collation)
    {
        $charType->setLength($this->extractLength($typeStr));
        $charType->setCollation($collation);

        return $charType;
    }

    /**
     * Create a new Character Type object.
     *
     * @return CharType
     */
    public function newType()
    {
        return new CharType();
    }

    /**
     * {@inheritdoc}
     */
    public function doRecognise($typeStr)
    {
        return 1 === preg_match('/^char\(\d+\)/i', $typeStr);
    }
}
