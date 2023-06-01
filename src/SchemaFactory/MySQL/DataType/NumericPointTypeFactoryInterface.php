<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\NumericPointTypeInterface;

interface NumericPointTypeFactoryInterface extends DataTypeFactoryInterface
{
    public function populate(NumericPointTypeInterface $numericPointType, $typeStr);

    public function newType();
}
