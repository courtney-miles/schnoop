<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 23/06/16
 * Time: 7:32 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

class TinyBlobType extends AbstractBlobType
{
    public function __construct()
    {
        parent::__construct(255);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_TINYBLOB;
    }
}
