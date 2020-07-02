<?php

namespace App\Tests;

use App\Crawler\GooglePodcastsCrawler;
use App\Scraper\GooglePodcasts\PodcastData;

class ExtractPodcastDataTest extends AbstractScraperTestCase
{
    public function testExtractPodcastData()
    {
        GooglePodcastsCrawler::extractPodcastData();
    }

    public function testExtractUsingHtml()
    {
        $html = file_get_contents(__DIR__ . '/html/podcast.html');
        $data = PodcastData::extract($html);
        $this->assertData($data);
    }

    private function assertData($data)
    {
        $this->assertEquals('Loen Talks', $data['name']);
        $this->assertEquals('Loen', $data['author']);
        $this->assertEquals('O herói do ocidente entrevista', $data['description']);
        $this->assertEquals('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRmikMIen74_1jUwfiW3oF7Z3OvqZJIOMaGFEYcxrP5RKUMCz7b', $data['image_url']);
        $this->assertEquals('https://anchor.fm/loentalks', $data['site_url']);
    }
}
