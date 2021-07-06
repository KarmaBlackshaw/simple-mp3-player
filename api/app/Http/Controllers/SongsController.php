<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Song;

class SongsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $playlist_id = $request->input('playlist_id');

        return DB::table('songs')
            ->leftJoin('song_playlists', 'song_playlists.song_id', 'songs.id')
            ->select('songs.*')
            ->when($playlist_id, function ($q, $playlist_id) {
                return $q->where('song_playlists.playlist_id', '=', $playlist_id);
            })
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $title = $request->input('title');
        $artist = $request->input('artist');
        $album = $request->input('album');
        $duration = $request->input('duration');
        $path = $request->file('file')->store('public');

        $filename = explode('/', $path)[1];



        $song = Song::create([
            'title' => $title,
            'artist' => $artist,
            'album' => $album,
            'duration' => $duration,
            'path' => $filename
        ]);

        return $song;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
