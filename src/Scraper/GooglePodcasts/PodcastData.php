<?php

namespace App\Scraper\GooglePodcasts;

use Symfony\Component\DomCrawler\Crawler;

class PodcastData
{
    public static function extract(string $html): array
    {
        return [
            'name' => self::extractTitle($html),
            'author' => self::extractAuthor($html),
            'description' => self::extractDescription($html),
            'image_url' => self::extractImageUrl($html),
            'site_url' => self::extractSiteUrl($html),
        ];
    }

    public static function extractTitle(string $html): string
    {
        $crawler = new Crawler($html);
        $node = $crawler->filterXPath('//html/head/meta[@itemprop="name"]');
        return $node->attr('content');
    }

    public static function extractAuthor(string $html): string
    {
        $crawler = new Crawler($html);
        return $crawler->filterXPath('//div[@role="list"]')
            ->parents()
            ->parents()
            ->parents()
            ->filterXPath('//div/div/div[2]')
            ->html();
    }

    public static function extractDescription(string $html): string
    {
        $crawler = new Crawler($html);
        $node = $crawler->filterXPath('//html/head/meta[@name="description"]');
        return $node->attr('content');
    }

    public static function extractImageUrl(string $html)
    {
        $crawler = new Crawler($html);
        $node = $crawler->filterXPath('//html/head/meta[@itemprop="image"]');
        return $node->count() === 0 ? null : $node->attr('content');
    }

    public static function extractSiteUrl(string $html): string
    {
        $crawler = new Crawler($html);
        return $crawler->filterXPath('//div[@role="list"]')
            ->parents()
            ->parents()
            ->parents()
            ->filterXPath('//div/div/div[3]//a')
            ->attr('href');
    }
}
