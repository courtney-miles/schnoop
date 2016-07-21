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
    public function __construct($collation)
    {
        parent::__construct(255, $collation);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::TYPE_TINYTEXT;
    }
}