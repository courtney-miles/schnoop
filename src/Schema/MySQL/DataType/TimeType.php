<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 11/07/16
 * Time: 4:16 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;


class TimeType extends AbstractTimeType
{
    /**
     * @return string
     */
    public function getName()
    {
        return self::TYPE_TIME;
    }
}