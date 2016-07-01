<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/06/16
 * Time: 9:21 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

class MediumIntType extends AbstractIntType
{
    public function __construct($displayWidth, $signed)
    {
        $minRange = $signed ? -8388608 : 0;
        $maxRange = $signed ? 8388607 : 16777215;

        parent::__construct($displayWidth, $signed, $minRange, $maxRange);
    }

    public function getType()
    {
        return self::TYPE_MEDIUMINT;
    }
}