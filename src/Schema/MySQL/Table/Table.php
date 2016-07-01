<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 2/06/16
 * Time: 5:01 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\Table;

use MilesAsylum\Schnoop\Schema\AbstractCommonTable;
use MilesAsylum\Schnoop\Schema\CommonColumnInterface;

class Table extends AbstractCommonTable implements TableInterface
{
    protected $engine;

    protected $defaultCollation;

    protected $rowFormat;

    protected $comment;

    /**
     * Table constructor.
     * @param $name
     * @param CommonColumnInterface[] $columns
     * @param $engine
     * @param $rowFormat
     * @param $collation
     * @param $comment
     */
    public function __construct($name, array $columns, $engine, $rowFormat, $collation, $comment)
    {
        parent::__construct($name, $columns);
        $this->setEngine($engine);
        $this->setDefaultCollation($collation);
        $this->setRowFormat($rowFormat);
        $this->setComment($comment);
    }

    /**
     * @return mixed
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * @return mixed
     */
    public function getDefaultCollation()
    {
        return $this->defaultCollation;
    }

    /**
     * @return mixed
     */
    public function getRowFormat()
    {
        return $this->rowFormat;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $engine
     */
    protected function setEngine($engine)
    {
        $this->engine = $engine;
    }

    /**
     * @param mixed $defaultCollation
     */
    protected function setDefaultCollation($defaultCollation)
    {
        $this->defaultCollation = $defaultCollation;
    }

    /**
     * @param mixed $rowFormat
     */
    protected function setRowFormat($rowFormat)
    {
        $this->rowFormat = $rowFormat;
    }

    /**
     * @param mixed $comment
     */
    protected function setComment($comment)
    {
        $this->comment = $comment;
    }
}