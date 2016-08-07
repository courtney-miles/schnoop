<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 11/07/16
 * Time: 4:30 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

class TimestampType extends AbstractTimeType
{
    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_TIMESTAMP;
    }
}