<?php

namespace App\Scraper\GooglePodcasts;

use Symfony\Component\DomCrawler\Crawler;

class PodcastLinks
{
    public static function extract(string $html): array
    {
        $crawler = new Crawler($html);
        $links = $crawler->filterXPath('//a[@href]')->each(function (Crawler $node) {
            return $node->attr('href');
        });
        $links = array_filter($links, function ($link) {
            return strpos($link, '/feed/') !== false;
        });
        $urlBase = 'https://podcasts.google.com/';
        return array_map(function ($link) use ($urlBase) {
            $link = substr($link, 2);
            $link = explode('?ved=', $link)[0];
            return $urlBase . $link;
        }, $links);
    }
}
