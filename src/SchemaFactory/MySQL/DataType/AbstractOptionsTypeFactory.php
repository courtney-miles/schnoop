<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\SchemaFactory\DataTypeFactoryInterface;

abstract class AbstractOptionsTypeFactory implements DataTypeFactoryInterface
{
    /**
     * @param $typeStr
     * @return array
     */
    protected static function getOptions($typeStr)
    {
        preg_match('/\((.*)\)/', $typeStr, $options);

        $options = explode(',', $options[1]);

        foreach ($options as $k => $option) {
            $options[$k] = trim($option, "'");
        }

        return $options;
    }
}
