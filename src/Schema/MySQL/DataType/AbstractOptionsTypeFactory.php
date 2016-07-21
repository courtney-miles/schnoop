<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/07/16
 * Time: 9:57 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

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
