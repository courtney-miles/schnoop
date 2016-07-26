<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 8/07/16
 * Time: 7:19 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\CollationTrait;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\OptionTrait;

class EnumType implements OptionsTypeInterface
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
        return self::TYPE_ENUM;
    }

    /**
     * @return bool
     */
    public function doesAllowDefault()
    {
        return true;
    }

    /**
     * @return mixed
     */
    public function cast($value)
    {
        return (string)$value;
    }
}
