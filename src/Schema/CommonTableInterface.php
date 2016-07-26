<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 4/06/16
 * Time: 9:12 AM
 */

namespace MilesAsylum\Schnoop\Schema;

interface CommonTableInterface
{
    public function getName();
    public function getColumnList();
    public function getColumns();
    public function hasColumn($columnName);
    public function getColumn($columnName);
    public function getIndexList();
    public function getIndexes();
    public function hasIndex($indexName);
    public function getIndex($indexName);
}
