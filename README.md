# Schnoop: PHP Database Schema Inspector

[![Build Status](https://travis-ci.org/courtney-miles/schnoop.svg?branch=master)](https://travis-ci.org/courtney-miles/schnoop) [![Coverage Status](https://coveralls.io/repos/github/courtney-miles/schnoop/badge.svg?branch=master)](https://coveralls.io/github/courtney-miles/schnoop?branch=master)

Schnoop provides a convenient PHP interface for inspecting a database schema.

Currently, only MySQL is supported.

## Examples

### Construct the Schnoop object

To construct the Schnoop object, first establish a PDO connection to your database server, then supply the connection to `SchnoopFactory::create()`.

```php
<?php

$conn = new \PDO('mysql:host=localhost', 'root');
$schnoop = SchnoopFactory::create($conn);
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
    print_r($table->getColumnList()) // Array of column names;
    print_r($table->getIndexList()) // Array of index names;
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
    var_export($column->isAllowNull()); // true == NULL, false == NOT NULL.
} else {
    echo "A column named, $columnName, does not exists for table {$table->getName()}.";
}
```

### Inspect the column data type

```php
<?php

// ...

$dataType = $column->getDataType();

echo $dataType->getName(); // INT, VARCHAR, TEXT or BLOB, etc.
```

### Inspect table indexes

```php
<?php

// ...

$indexName = 'PRIMARY KEY';

if ($table->hasIndex($indexName)) {
    $index = $table->getIndex($indexName);
    
    echo $index->getType(); // index, unique, fulltext or spatial.
    echo $index->getIndexType(); // hash, btree, rtree or fulltext.
    
    foreach ($index->getIndexedColumns as $indexedColumn) {
        echo $indexedColumn->getColumn()->getName(); // The name of the column in the index.
        echo $indexedColumn->getCollation(); // The collation (i.e. Asc) of the index on the column.
    }
}
```

## Changelog

<!--
### Version 0.1.0-alpha.3

* Objects for describing a database schema has been shifted into a separate package, [schnoop-schema](https://github.com/courtney-miles/schnoop-schema).
-->

### Version 0.1.0-alpha.2

* Indexes on a table can be examined. 

### Version 0.1.0-alpha.1

* Databases can be listed.
* Properties of a database can be examined.
* Tables can be listed for a database.
* Properties of a table can be examined.
* Table columns can be listed.
* Properties of a column can be examined.
