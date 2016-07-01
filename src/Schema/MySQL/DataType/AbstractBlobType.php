<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 1/07/16
 * Time: 7:11 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

abstract class AbstractBlobType extends AbstractBinaryType implements BlobTypeInterface
{
    public function allowDefault()
    {
        return false;
    }
}