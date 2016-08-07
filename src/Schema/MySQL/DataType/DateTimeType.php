<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 11/07/16
 * Time: 4:23 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

class DateTimeType extends AbstractTimeType
{
    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_DATETIME;
    }
}
