<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

abstract class AbstractOptionsTypeFactory implements DataTypeFactoryInterface
{
    /**
     * Extract the options from the supplied type string.
     *
     * @param string $typeStr
     *
     * @return array options
     */
    protected static function extractOptions($typeStr)
    {
        preg_match('/\((.*)\)/', $typeStr, $options);

        $options = explode(',', $options[1]);

        foreach ($options as $k => $option) {
            $options[$k] = trim($option, "'");
        }

        return $options;
    }
}
