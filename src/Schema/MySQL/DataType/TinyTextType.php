<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 23/06/16
 * Time: 7:07 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

class TinyTextType extends AbstractTextType
{
    public function __construct($characterSet, $collation)
    {
        parent::__construct(255, $characterSet, $collation);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_TINYTEXT;
    }
}