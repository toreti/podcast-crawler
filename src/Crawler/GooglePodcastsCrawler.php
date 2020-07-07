<?php

namespace App\Crawler;

use App\Persistence\Eloquent\Episode;
use App\Persistence\Eloquent\Podcast;
use App\Repositories\EpisodeRepository;
use App\Repositories\PodcastRepository;
use App\Scraper\GooglePodcasts\EpisodeData;
use App\Scraper\GooglePodcasts\EpisodeLinks;
use App\Scraper\GooglePodcasts\PodcastData;
use App\Scraper\GooglePodcasts\PodcastLinks;
use App\Selenium\Selenium;
use Facebook\WebDriver\WebDriverBy;

/**
 * @todo unificar extração de dados do podcast e seus episódios, pois ambos utilizam a mesma tela
 */
class GooglePodcastsCrawler
{
    public static function extractPodcastLinksFromHome(): void
    {
        $url = 'https://podcasts.google.com/';
        $driver = Selenium::createChrome($url);
        $html = $driver->findElement(WebDriverBy::tagName('html'))->getAttribute('outerHTML');
        $links = PodcastLinks::extract($html);
        Podcast::storeLinks($links);
        $driver->quit();
    }

    public static function extractPodcastData(): void
    {
        $podcasts = Podcast::getWithoutData();
        $secondsSleepingAfterOpenBrowser = 2;
        foreach ($podcasts as $podcast) {
            $driver = Selenium::createChrome($podcast->google_podcast_url, $secondsSleepingAfterOpenBrowser);
            $html = $driver->findElement(WebDriverBy::tagName('html'))->getAttribute('outerHTML');
            $data = PodcastData::extract($html);
            PodcastRepository::updateData($podcast->id, $data);
            $driver->quit();
        }
    }

    public static function extractEpisodeLinks(): void
    {
        $podcasts = Podcast::getWithData();
        $secondsSleepingAfterOpenBrowser = 1;
        foreach ($podcasts as $podcast) {
            $driver = Selenium::createChrome($podcast->google_podcast_url, $secondsSleepingAfterOpenBrowser);
            $html = $driver->findElement(WebDriverBy::tagName('html'))->getAttribute('outerHTML');
            $links = EpisodeLinks::extract($html);
            Episode::storeLinks($podcast->id, $links);
            $driver->quit();
        }
    }

    public static function extractEpisodeData($limit = 200): void
    {
        $episodes = Episode::getWithoutData($limit);
        $secondsSleepingAfterOpenBrowser = 3;
        foreach ($episodes as $episode) {
            $driver = Selenium::createChrome($episode->google_podcast_url, $secondsSleepingAfterOpenBrowser);
            $driver->findElement(WebDriverBy::xpath('//span[@role="presentation"]'))->click();
            $html = $driver->findElement(WebDriverBy::tagName('html'))->getAttribute('outerHTML');
            $data = EpisodeData::extract($html);
            EpisodeRepository::updateData($episode->id, $data);
            $driver->quit();
        }
    }
}
