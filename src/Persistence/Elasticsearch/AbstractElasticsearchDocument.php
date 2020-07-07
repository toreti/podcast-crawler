<?php

namespace App\Persistence\Elasticsearch;

use Elasticsearch\ClientBuilder;

abstract class AbstractElasticsearchDocument
{
    protected $client;
    protected $params;

    public function __construct()
    {
        $this->client = ClientBuilder::create()->build();
        $this->params = [
            'refresh' => true,
        ];
    }

    abstract public function store(array $data): void;
}
