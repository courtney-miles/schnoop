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
    public function newDatabase(array $rawDatabase, Schnoop $schnoop);
    
    /**
     * @param array $rawTable
     * @param array $rawColumns
     * @return CommonTableInterface
     */
    public function newTable(array $rawTable, array $rawColumns);

    /**
     * @param array $rawColumn
     * @return CommonColumnInterface
     */
    public function newColumn(array $rawColumn);
}