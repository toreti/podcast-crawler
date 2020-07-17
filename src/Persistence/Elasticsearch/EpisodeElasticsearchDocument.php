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
            'doc' => [
                'slug_podcast' => $data['slug_podcast'],
                'slug_episode' => $data['slug_episode'],
                'title' => $data['title'],
                'description' => $data['description'],
            ],
        ];
        $this->client->create($this->params);
    }

    public function update(array $data, $podcastSlug): void
    {
        $this->params['id'] = $data['id'];
        $this->params['body'] = [
            'doc' => [
                'slug_podcast' => $podcastSlug,
                'slug_episode' => $data['slug'],
                'title' => $data['title'],
                'description' => $data['description'],
            ],
        ];
        $this->client->update($this->params);
    }
}
