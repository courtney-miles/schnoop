<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/06/16
 * Time: 9:19 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

class TinyIntType extends AbstractIntType
{
    public function __construct($displayWidth, $signed)
    {
        $minRange = $signed ? -128 : 0;
        $maxRange = $signed ? 127 : 255;

        parent::__construct($displayWidth, $signed, $minRange, $maxRange);
    }

    public function getType()
    {
        return self::TYPE_TINYINT;
    }
}