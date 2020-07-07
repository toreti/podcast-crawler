<?php

namespace App\Persistence\Elasticsearch;

class PodcastElasticsearchDocument extends AbstractElasticsearchDocument
{
    public function __construct()
    {
        parent::__construct();
        $this->params['index'] = $_ENV['ELASTICSEARCH_PODCASTS_INDEX'];
    }

    public function store(array $data): void
    {
        $this->params['id'] = $data['id'];
        $this->params['body'] = [
            'name' => $data['name'],
            'author' => $data['author'],
            'description' => $data['description'],
        ];
        $this->client->create($this->params);
    }
}
