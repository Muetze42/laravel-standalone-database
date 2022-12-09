<?php

require __DIR__.'/../../vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Migrations\MigrationRepositoryInterface;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Events\Dispatcher;

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

// Set the event dispatcher used by Eloquent models... (optional)
//$capsule->setEventDispatcher(new Dispatcher(new Container));
// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();
// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
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
$migrator->run($paths); // Run
