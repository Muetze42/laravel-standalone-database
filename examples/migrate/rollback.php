<?php

require __DIR__.'/../../vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Illuminate\Database\Migrations\Migrator;

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

$container = Container::getInstance();
$databaseMigrationRepository = new DatabaseMigrationRepository($capsule->getDatabaseManager(), 'migration');
if (!$databaseMigrationRepository->repositoryExists()) {
    $databaseMigrationRepository->createRepository();
}
$container->instance(MigrationRepositoryInterface::class, $databaseMigrationRepository);
$container->instance(ConnectionResolverInterface::class, $capsule->getDatabaseManager());

$paths = [
    __DIR__.'/migrations',
];

$migrator = $container->make(Migrator::class);
$migrator->rollback($paths);
