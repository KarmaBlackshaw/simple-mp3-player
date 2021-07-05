<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $table = 'playlists';

    protected $primaryKey = 'id';

    protected $fillable = ['name'];
}
