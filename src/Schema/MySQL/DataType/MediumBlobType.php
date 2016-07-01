<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 23/06/16
 * Time: 7:33 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

class MediumBlobType extends AbstractBlobType
{
    public function __construct()
    {
        parent::__construct(16777215);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_MEDIUMBLOB;
    }
}
