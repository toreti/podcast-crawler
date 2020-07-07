<?php

namespace App\Repositories;

use App\Persistence\Elasticsearch\EpisodeElasticsearchDocument;
use App\Persistence\Eloquent\Episode;

class EpisodeRepository
{
    public static function updateData(int $episodeId, array $data): void
    {
        Episode::updateData($episodeId, $data);
        $data['id'] = $episodeId;
        (new EpisodeElasticsearchDocument())->store($data);
    }
}
