<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 23/06/16
 * Time: 7:24 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

class LongTextType extends AbstractTextType
{
    public function __construct($characterSet, $collation)
    {
        parent::__construct(4294967295, $characterSet, $collation);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_LONGTEXT;
    }
}