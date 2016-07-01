<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 23/06/16
 * Time: 7:34 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

class LongBlobType extends AbstractBlobType
{
    public function __construct()
    {
        parent::__construct(4294967295);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_LONGBLOB;
    }
}