<?php

namespace App\Scraper\GooglePodcasts;

use Symfony\Component\DomCrawler\Crawler;

/**
 * @todo instanciar apenas uma vez o Crawler
 */
class EpisodeData
{
    public static function extract(string $html): array
    {
        return [
            'title' => self::extractTitle($html),
            'description' => self::extractDescription($html),
            'date' => self::extractDate($html),
            'duration' => self::extractDuration($html),
            'image_url' => self::extractImageUrl($html),
            'audio_url' => self::extractAudioUrl($html),
            'google_podcast_url' => self::extractUrl($html),
        ];
    }

    public static function extractTitle(string $html): string
    {
        $crawler = new Crawler($html);
        return $crawler->filterXPath('//span[@role="presentation"]')
            ->parents()
            ->parents()
            ->parents()
            ->parents()
            ->filterXPath('//div/div[1]/div[1]')
            ->html();
    }

    public static function extractDescription(string $html): string
    {
        $crawler = new Crawler($html);
        return $crawler->filterXPath('//html/head/meta[@name="description"]')->attr('content');
    }

    public static function extractDate(string $html): string
    {
        return self::extractDateAndDuration($html)['date'];
    }

    /**
     * @param string $html
     * @return string|null
     */
    public static function extractDuration(string $html)
    {
        return self::extractDateAndDuration($html)['duration'];
    }

    public static function extractAudioUrl(string $html): string
    {
        $crawler = new Crawler($html);
        return $crawler->filterXPath('//audio')->attr('src');
    }

    public static function extractImageUrl(string $html): string
    {
        $crawler = new Crawler($html);
        return $crawler->filterXPath('//html/head/meta[@itemprop="image"]')->attr('content');
    }

    private function extractDateAndDuration(string $html): array
    {
        $crawler = new Crawler($html);
        $stringAndDuration = $crawler->filterXPath('//span[@role="presentation"]')
            ->parents()
            ->parents()
            ->parents()
            ->parents()
            ->filterXPath('//div/div[2]')
            ->html();
        $parts = explode('·', $stringAndDuration);
        return [
            'date' => substr($parts[0], 0, -2),
            'duration' => isset($parts[1]) ? substr($parts[1], 2) : null,
        ];
    }

    public static function extractUrl(string $html): string
    {
        $crawler = new Crawler($html);
        return $crawler->filterXPath('//html/head/meta[@property="og:url"]')->attr('content');
    }
}
