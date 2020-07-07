<?php

namespace App\Tests;

use Dotenv\Dotenv;
use Elasticsearch\ClientBuilder;
use Illuminate\Database\Capsule\Manager as DB;
use PHPUnit\Framework\TestCase;

abstract class AbstractScraperTestCase extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();
        self::connectMySQL();
        self::connectElasticsearch();
    }

    private static function connectMySQL()
    {
        $capsule = new DB;
        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'podcast',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

    private static function connectElasticsearch()
    {
        $client = ClientBuilder::create()->build();
        $indexes = [$_ENV['ELASTICSEARCH_PODCASTS_INDEX'], $_ENV['ELASTICSEARCH_EPISODES_INDEX']];
        foreach ($indexes as $index) {
            $index = ['index' => $index];
            if (!$client->indices()->exists($index)) {
                $client->indices()->create($index);
            }
        }
    }
}
