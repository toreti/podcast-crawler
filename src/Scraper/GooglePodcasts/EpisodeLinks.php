<?php

namespace App\Scraper\GooglePodcasts;

use Symfony\Component\DomCrawler\Crawler;

class EpisodeLinks
{
    public static function extract(string $html)
    {
        $crawler = new Crawler($html);
        $links = $crawler->filterXPath('//a[@role="listitem"]')->each(function (Crawler $node) {
            return $node->attr('href');
        });
        $urlBase = 'https://podcasts.google.com/';
        $links = array_reverse($links);
        return array_map(function ($link) use ($urlBase) {
            $link = substr($link, 2);
            $link = explode('?ved=', $link)[0];
            return $urlBase . $link;
        }, $links);
    }
}
