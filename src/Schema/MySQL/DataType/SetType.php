<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 8/07/16
 * Time: 7:34 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\CollationTrait;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\OptionTrait;

class SetType implements OptionsTypeInterface
{
    use OptionTrait;
    use CollationTrait;
    
    public function __construct(array $options, $collation)
    {
        $this->setOptions($options);
        $this->setCollation($collation);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::TYPE_SET;
    }

    /**
     * @return bool
     */
    public function doesAllowDefault()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     * @param array $value
     * @return array
     */
    public function cast($value)
    {
        if (!empty($value)) {
            foreach ($value as $k => $v) {
                $value[$k] = (string)$v;
            }
        }

        return $value;
    }
}