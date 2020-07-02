<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $fillable = ['podcast_id', 'title', 'description', 'date', 'duration', 'image_url', 'audio_url', 'google_podcast_url'];

    public static function storeLinks(int $podcastId, array $links)
    {
        foreach ($links as $link) {
            if (self::where('google_podcast_url', $link)->exists()) {
                continue;
            }
            self::create([
                'podcast_id' => $podcastId,
                'google_podcast_url' => $link,
            ]);
        }
    }

    public static function getWithoutData($limit = 10)
    {
        return self::whereNull('title')
            ->whereNull('deleted_at')
            ->limit($limit)
            ->get();
    }

    public static function updateData(int $id, array $data)
    {
        return self::where('id', $id)->update($data);
    }
}
