<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/06/16
 * Time: 9:03 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

class BlobType extends AbstractBlobType
{
    public function __construct()
    {
        parent::__construct(65535);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::TYPE_BLOB;
    }
}