<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 18/06/16
 * Time: 11:25 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

class IntType extends AbstractIntType
{
    public function __construct($displayWidth, $signed)
    {
        $minRange = $signed ? -2147483648 : 0;
        $maxRange = $signed ? 2147483647 : 4294967295;
        
        parent::__construct($displayWidth, $signed, $minRange, $maxRange);
    }

    public function getName()
    {
        return self::TYPE_INT;
    }
}