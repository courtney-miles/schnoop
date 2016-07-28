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
    const LENGTH = 4294967295;

    public function __construct($collation)
    {
        parent::__construct(self::LENGTH, $collation);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::TYPE_LONGTEXT;
    }
}