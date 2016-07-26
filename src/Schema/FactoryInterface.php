<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 4/06/16
 * Time: 8:38 AM
 */

namespace MilesAsylum\Schnoop\Schema;

use MilesAsylum\Schnoop\Schnoop;

interface FactoryInterface
{
    /**
     * @param array $rawDatabase
     * @param Schnoop $schnoop
     * @return CommonDatabaseInterface
     */
    public function createDatabase(array $rawDatabase, Schnoop $schnoop);

    /**
     * @param array $rawTable
     * @param array $rawColumns
     * @param array $rawIndexes
     * @return CommonTableInterface
     */
    public function createTable(array $rawTable, array $rawColumns, array $rawIndexes);

    /**
     * @param array $rawColumn
     * @return CommonColumnInterface
     */
    public function createColumn(array $rawColumn);
}