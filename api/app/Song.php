<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $table = 'songs';

    protected $primaryKey = 'id';

    protected $fillable = ['title', 'artist', 'album', 'path'];

    public function scopePlaylist($query, $playlist)
    {
        if ($playlist) {
            return $query->where('song_playlists.id', '=',  $playlist);
        }

        return $query;
    }
}
