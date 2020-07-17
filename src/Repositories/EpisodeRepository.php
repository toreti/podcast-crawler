<?php

namespace App\Repositories;

use App\Persistence\Elasticsearch\EpisodeElasticsearchDocument;
use App\Persistence\Eloquent\Episode;
use Cocur\Slugify\Slugify;

class EpisodeRepository
{
    public static function updateData(int $episodeId, array $data): void
    {
        $data['slug'] = (new Slugify())->slugify($data['title']);
        $episode = Episode::find($episodeId);
        $episode->update($data);
        $data['id'] = $episodeId;
        $data['slug_podcast'] = $episode->podcast->slug;
        $data['slug_episode'] = $data['slug'];
        unset($data['slug']);
        (new EpisodeElasticsearchDocument())->store($data);
    }
}
