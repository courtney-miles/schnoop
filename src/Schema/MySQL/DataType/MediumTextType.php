<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 23/06/16
 * Time: 7:16 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

class MediumTextType extends AbstractTextType
{
    const LENGTH = 16777215;

    public function __construct($collation)
    {
        parent::__construct(self::LENGTH, $collation);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::TYPE_MEDIUMTEXT;
    }
}