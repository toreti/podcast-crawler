<?php

namespace App\Tests;

use App\Crawler\GooglePodcastsCrawler;
use App\Persistence\Eloquent\Podcast;
use App\Scraper\GooglePodcasts\PodcastLinks;

class ExtractPodcastLinksTest extends AbstractScraperTestCase
{
    public function testExtractPodcastLinksFromHome()
    {
        GooglePodcastsCrawler::extractPodcastLinksFromHome();
    }

    public function testExtractUsingHtml()
    {
        $html = file_get_contents(__DIR__ . '/html/home.html');
        $links = PodcastLinks::extract($html);
        Podcast::storeLinks($links);
    }
}
