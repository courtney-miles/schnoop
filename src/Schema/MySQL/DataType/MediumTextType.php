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
    public function __construct($characterSet, $collation)
    {
        parent::__construct(16777215, $characterSet, $collation);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_MEDIUMTEXT;
    }
}