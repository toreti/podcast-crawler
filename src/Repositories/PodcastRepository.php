<?php

namespace App\Repositories;

use App\Persistence\Elasticsearch\PodcastElasticsearchDocument;
use App\Persistence\Eloquent\Podcast;
use Cocur\Slugify\Slugify;

class PodcastRepository
{
    public static function updateData(int $podcastId, array $data): void
    {
        $data['slug'] = $slug = (new Slugify())->slugify($data['name']);
        Podcast::updateData($podcastId, $data);
        $data['id'] = $podcastId;
        (new PodcastElasticsearchDocument())->store($data);
    }
}
