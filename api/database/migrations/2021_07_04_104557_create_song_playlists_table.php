<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSongPlaylistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('song_playlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('song_id');
            $table->foreignId('playlist_id');
            $table->timestamps();

            $table->foreign('song_id')->references('id')->on('songs');
            $table->foreign('playlist_id')->references('id')->on('playlists');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('song_playlists');
    }
}
