<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/07/16
 * Time: 6:19 PM.
 */

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\BinaryType;

class BinaryTypeFactory extends AbstractCharTypeFactory
{
    /**
     * {@inheritdoc}
     */
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        return $this->populate($this->newType(), $typeStr);
    }

    /**
     * Populate the properties of the supplied binary type from the type string.
     *
     * @param string $typeStr
     *
     * @return BinaryType
     */
    public function populate(BinaryType $binaryType, $typeStr)
    {
        $binaryType->setLength($this->extractLength($typeStr));

        return $binaryType;
    }

    /**
     * Create a new binary type.
     *
     * @return BinaryType
     */
    public function newType()
    {
        return new BinaryType();
    }

    /**
     * {@inheritdoc}
     */
    public function doRecognise($typeStr)
    {
        return 1 === preg_match('/^binary\(\d+\)/i', $typeStr);
    }
}
