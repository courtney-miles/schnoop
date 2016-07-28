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
    const LENGTH = 255;

    public function __construct()
    {
        parent::__construct(self::LENGTH);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::TYPE_TINYBLOB;
    }
}
