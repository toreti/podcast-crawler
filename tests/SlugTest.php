<?php

namespace App\Tests;

use App\Persistence\Elasticsearch\EpisodeElasticsearchDocument;
use App\Persistence\Elasticsearch\PodcastElasticsearchDocument;
use App\Persistence\Eloquent\Episode;
use App\Persistence\Eloquent\Podcast;
use Cocur\Slugify\Slugify;

class SlugTest extends AbstractScraperTestCase
{
    public function testUpdatePodcastsSlugInDB()
    {
        $slugify = new Slugify();
        $podcasts = Podcast::whereNull('slug')->whereNotNull('name')->get();
        foreach ($podcasts as $podcast) {
            $podcast->slug = $slugify->slugify($podcast->name);
            $podcast->save();
        }
    }

    public function testUpdateEpisodesSlugInDB()
    {
        $slugify = new Slugify();
        $episodes = Episode::whereNull('slug')->whereNotNull('title')->get();
        foreach ($episodes as $episode) {
            $episode->slug = $slugify->slugify($episode->title);
            $episode->save();
        }
    }

    public function testUpdatePodcastsSlugInES()
    {
        $podcasts = Podcast::whereNotNull('slug')->get();
        $podcastElasticsearchDocument = new PodcastElasticsearchDocument();
        foreach ($podcasts as $podcast) {
            $data = $podcast->toArray();
            $podcastElasticsearchDocument->update($data);
        }
    }

    public function testUpdateEpisodesSlugInES()
    {
        $episodes = Episode::whereNotNull('slug')->get();
        $episodeElasticsearchDocument = new EpisodeElasticsearchDocument();
        foreach ($episodes as $episode) {
            $podcastSlug = $episode->podcast->slug;
            $data = $episode->toArray();
            $episodeElasticsearchDocument->update($data, $podcastSlug);
        }
    }
}
