<?php

namespace App\Persistence\Elasticsearch;

class EpisodeElasticsearchDocument extends AbstractElasticsearchDocument
{
    public function __construct()
    {
        parent::__construct();
        $this->params['index'] = $_ENV['ELASTICSEARCH_EPISODES_INDEX'];
    }

    public function store(array $data): void
    {
        $this->params['id'] = $data['id'];
        $this->params['body'] = [
            'title' => $data['title'],
            'description' => $data['description'],
        ];
        $this->client->create($this->params);
    }
}
