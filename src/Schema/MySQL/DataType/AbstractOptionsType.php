<?php

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\CollationTrait;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\OptionTrait;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\QuoteStringTrait;

abstract class AbstractOptionsType implements OptionsTypeInterface
{
    use OptionTrait;
    use CollationTrait;
    use QuoteStringTrait;

    public function __construct(array $options, $collation)
    {
        $this->setOptions($options);
        $this->setCollation($collation);
    }

    /**
     * @return bool
     */
    public function doesAllowDefault()
    {
        return true;
    }

    public function __toString()
    {
        return sprintf(
            "%s(%s) COLLATE %s",
            strtoupper($this->getType()),
            "'" . implode("', ", $this->getOptions()) . "'",
            $this->getCollation()
        );
    }
}
