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
    const LENGTH = 4294967295;

    public function __construct()
    {
        parent::__construct(self::LENGTH);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::TYPE_LONGBLOB;
    }
}