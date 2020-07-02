<?php

namespace App\Tests;

use App\Crawler\GooglePodcastsCrawler;
use App\Models\Episode;
use App\Scraper\GooglePodcasts\EpisodeLinks;

class ExtractEpisodeLinksTest extends AbstractScraperTestCase
{
    public function testExtractEpisodeLinks()
    {
        GooglePodcastsCrawler::extractEpisodeLinks();
    }

    public function testExtractUsingHtml()
    {
        $html = file_get_contents(__DIR__ . '/html/podcast.html');
        $links = EpisodeLinks::extract($html);
        Episode::storeLinks($links);
    }
}
