<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Podcast extends Model
{
    protected $fillable = ['name', 'author', 'description', 'image_url', 'google_podcast_url'];

    public static function storeLinks(array $links)
    {
        foreach ($links as $link) {
            if (self::where('google_podcast_url', $link)->exists()) {
                continue;
            }
            self::create([
                'google_podcast_url' => $link,
            ]);
        }
    }

    public static function getWithoutData($limit = 100)
    {
        return self::whereNull('name')
            ->whereNull('deleted_at')
            ->limit($limit)
            ->get();
    }

    public static function getWithData($limit = 100)
    {
        return self::whereNotNull('name')
            ->whereNull('deleted_at')
            ->limit($limit)
            ->get();
    }

    public static function updateData(int $id, array $data)
    {
        return self::where('id', $id)->update($data);
    }
}
