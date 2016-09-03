<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\SchemaFactory\DataTypeFactoryInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\NumericPointTypeInterface;

interface NumericPointTypeFactoryInterface extends DataTypeFactoryInterface
{
    /**
     * @param NumericPointTypeInterface $numericPointType
     * @param $typeStr
     * @return NumericPointTypeInterface
     */
    public function populate(NumericPointTypeInterface $numericPointType, $typeStr);

    /**
     * @return NumericPointTypeInterface
     */
    public function newType();
}
