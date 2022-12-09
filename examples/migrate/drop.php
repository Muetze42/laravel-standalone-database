<?php

require __DIR__.'/../../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'database',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);


$capsule->setAsGlobal();
$capsule->bootEloquent();

$connection = $capsule->getConnection();
$connection->getSchemaBuilder()->dropAllTables();
//$connection->getSchemaBuilder()->dropAllViews();
//$connection->getSchemaBuilder()->dropAllTypes();
