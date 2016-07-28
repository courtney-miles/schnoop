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
    const LENGTH = 65535;

    public function __construct()
    {
        parent::__construct(self::LENGTH);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::TYPE_BLOB;
    }
}