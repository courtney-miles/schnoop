<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 28/06/16
 * Time: 4:53 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\LengthTrait;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\NumericRangeTrait;

class BitType implements BitTypeInterface
{
    use LengthTrait;
    use NumericRangeTrait;

    public function __construct($length)
    {
        $this->setLength($length);
        $this->setRange(0, pow(2, $length));
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return self::TYPE_BIT;
    }

    /**
     * @return mixed
     */
    public function cast($value)
    {
        return (int)$value;
    }
    
    public function doesAllowDefault()
    {
        return true;
    }
}