<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/06/16
 * Time: 8:58 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

class TextType extends AbstractTextType
{
    public function __construct($characterSet, $collation)
    {
        parent::__construct(65535, $characterSet, $collation);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_TEXT;
    }
}