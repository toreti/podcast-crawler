<?php

namespace App\Tests;

use App\Crawler\GooglePodcastsCrawler;
use App\Persistence\Elasticsearch\EpisodeElasticsearchDocument;
use App\Persistence\Eloquent\Episode;
use App\Scraper\GooglePodcasts\EpisodeData;

class ExtractEpisodeDataTest extends AbstractScraperTestCase
{
    public function testExtractEpisodeData()
    {
        GooglePodcastsCrawler::extractEpisodeData(10000);
    }

    public function testStoreInElasticsearch()
    {
        $episodes = Episode::whereNull('deleted_at')
            ->whereNotNull('title')
            ->get();
        $episodeDocument = new EpisodeElasticsearchDocument();
        foreach ($episodes as $episode) {
            $data = [
                'id' => $episode->id,
                'title' => $episode->title,
                'description' => $episode->description,
            ];
            $episodeDocument->store($data);
        }
    }

    public function testExtractUsingHtml()
    {
        $html = file_get_contents(__DIR__ . '/html/episode.html');
        $html = file_get_contents(__DIR__ . '/html/episode-nerdcast.html');
        $episode = EpisodeData::extract($html);
        $this->assertEpisode($episode);
    }

    private function assertEpisode(array $episode)
    {
        $this->assertEquals('https://podcasts.google.com/feed/aHR0cHM6Ly9hbmNob3IuZm0vcy8xM2E0ZjkzMC9wb2RjYXN0L3Jzcw/episode/MjM2ODVmZDktYTA5Zi00MTFlLWE0MDktY2ZlYmM2ZjcyMDQ4', $episode['url']);
        $this->assertEquals('https://anchor.fm/s/13a4f930/podcast/play/15404381/https%3A%2F%2Fd3ctxlq1ktw2nl.cloudfront.net%2Fproduction%2F2020-5-19%2F83562585-44100-2-578eb0e9ebc8a.mp3', $episode['link']);
        $this->assertEquals('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRmikMIen74_1jUwfiW3oF7Z3OvqZJIOMaGFEYcxrP5RKUMCz7b', $episode['image']);
        $this->assertEquals('Loen Talks #15 - Mirai Loen e o futuro que eu tentei evitar', $episode['title']);
        $this->assertEquals('mirai loen

x

o fim do que conhecemos', $episode['description']);
        $this->assertEquals('19 de jun. de 2020', $episode['date']);
        $this->assertEquals('20 min', $episode['duration']);
    }
}
