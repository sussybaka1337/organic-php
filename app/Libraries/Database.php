<?php

namespace App\Libraries;
use Illuminate\Database\Capsule\Manager;

class Database
{
    public static function init()
    {
        $manager = new Manager();
        $config = [
            'driver' => 'mysql',
            'host' => $_ENV['DATABASE_HOST'],
            'database' => $_ENV['DATABASE_NAME'],
            'username' => $_ENV['DATABASE_USER'],
            'password' => $_ENV['DATABASE_PASS'],
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ];
        $manager->addConnection($config);
        $manager->setAsGlobal();
        $manager->bootEloquent();
    }
    public static function runSchemaBuilder()
    {
        // Manager::schema()->dropAllTables();
    }
}