<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SongPlaylist extends Model
{
    protected $table = 'song_playlists';

    protected $primaryKey = 'id';

    protected $fillable = ['song_id', 'playlist_id'];
}
