<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/06/16
 * Time: 9:22 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

class BigIntType extends AbstractIntType
{
    public function __construct($displayWidth, $signed)
    {
        $minRange = $signed ? (int)'-9223372036854775808' : 0;
        $maxRange = $signed ? 9223372036854775807 : 18446744073709551615;

        parent::__construct($displayWidth, $signed, $minRange, $maxRange);
    }

    public function getName()
    {
        return self::TYPE_BIGINT;
    }
}
