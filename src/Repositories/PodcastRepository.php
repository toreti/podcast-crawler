<?php

namespace App\Repositories;

use App\Persistence\Elasticsearch\PodcastElasticsearchDocument;
use App\Persistence\Eloquent\Podcast;

class PodcastRepository
{
    public static function updateData(int $podcastId, array $data): void
    {
        Podcast::updateData($podcastId, $data);
        $data['id'] = $podcastId;
        (new PodcastElasticsearchDocument())->store($data);
    }
}
