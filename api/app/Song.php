<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $table = 'songs';

    protected $primaryKey = 'id';

    protected $fillable = ['title', 'artist', 'album', 'path', 'duration'];
}
