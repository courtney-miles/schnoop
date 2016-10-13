# Schnoop: PHP Database Schema Inspector

[![Build Status](https://travis-ci.org/courtney-miles/schnoop.svg?branch=master)](https://travis-ci.org/courtney-miles/schnoop) [![Coverage Status](https://coveralls.io/repos/github/courtney-miles/schnoop/badge.svg?branch=master)](https://coveralls.io/github/courtney-miles/schnoop?branch=master) [![Latest Stable Version](https://poser.pugx.org/milesasylum/schnoop/v/stable)](https://packagist.org/packages/milesasylum/schnoop) [![Total Downloads](https://poser.pugx.org/milesasylum/schnoop/downloads)](https://packagist.org/packages/milesasylum/schnoop) [![License](https://poser.pugx.org/milesasylum/schnoop/license)](https://packagist.org/packages/milesasylum/schnoop)

Schnoop provides a convenient PHP interface for inspecting a MySQL database schema and producing the DDL statements for the schema.

It is intended to assist with code generation in development environments.

> **Disclaimer:** It is not advisable to use this package in production environments.

## Examples

### Construct the Schnoop object

To construct the Schnoop object, first establish a PDO connection to your database server, then supply the connection to `SchnoopFactory::create()`.

```php
<?php
use \MilesAsylum\Schnoop;

$conn = new \PDO('mysql:host=localhost', 'root');
$schnoop = Schnoop::createSelf($conn);
```

### Get a list of databases

```php
<?php
// ...

$databaseList = $schnoop->getDatabaseList;

print_r($databaseList);
```

### Inspect a database

```php
<?php
// ...

$databaseName = 'acme_db';

if ($schnoop->hasDatabase($databaseName)) {
    $database = $schnoop->getDatabase($databaseName);
    
    echo $database->getName(); // acme_database
    echo $database->getDefaultCollation(); // I.e. utf8mb4_general_ci
    print_r($database->getTableList()); // Array of table names.
} else {
    echo "A database named, $databaseName, cannot be found on the server.";
}
```

### Inspect a table

```php
<?php
// ...

$tableName = 'acme_tbl';

if ($database->hasTable($tableName){
    $table = $database->getTable($tableName);
    
    echo $table->getName(); // acme_tbl
    echo $table->getEngine(); // I.e. InnoDB
    echo $table->getDefaultCollation(); // I.e. utf8mb_general_ci
    echo $table->getRowFormat(); // I.e. dynamic
    print_r($table->getColumnList()); // Array of column names;
    print_r($table->getIndexList()); // Array of index names;
} else {
    echo "A table named, $tableName, cannot be found in {$database->getName()}";
}
```

### Inspect a column

```php
<?php
// ...

$columnName = 'acme_col';

if ($table->hasColumn($columnName) {
    $column = $table->getColumn($columnName);
    
    echo $column->getName(); // The name of the column.
    echo $column->getDefault(); // I.e. The default value of the column.
    var_export($column->isNullable()); // true == NULL, false == NOT NULL.
} else {
    echo "A column named, $columnName, does not exists for table {$table->getName()}.";
}
```

### Inspect the column data type

```php
<?php
// ...

$dataType = $column->getDataType();

echo $dataType->getType(); // INT, VARCHAR, TEXT or BLOB, etc.
```

### Inspect table indexes

```php
<?php
// ...

$indexName = 'PRIMARY KEY';

if ($table->hasIndex($indexName)) {
    $index = $table->getIndex($indexName);
    
    echo $index->getConstraintType(); // index, unique, fulltext or spatial.
    echo $index->getIndexType(); // hash, btree, rtree or fulltext.
    
    foreach ($index->getIndexedColumns as $indexedColumn) {
        echo $indexedColumn->getColumnName(); // The name of the column in the index.
        echo $indexedColumn->getLength(); // The index prefix length.
        echo $indexedColumn->getCollation(); // The collation (i.e. Asc) of the index on the column.
    }
}
```

### Inspect table triggers
```php
<?php
// ...

$triggers = $table->getTriggers();

foreach ($triggers as $trigger)
{
    echo $trigger->getName(); // The trigger name.
    echo $trigger->getEvent(); // INSERT, UPDATE or DELETE.
    echo $trigger->getTiming(); // BEFORE or AFTER.
    echo $trigger->getBody(); // The trigger logic.     
}

````

## Todo

* Add method `DataTypeFactoryInterface::getTypeName()` to be used with `DataTypeFactory::addHandler()`.
* Introduced a repository to prevent duplicate resources from being constructed.
* Add support for Views.
