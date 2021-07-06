<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Playlist;
use App\SongPlaylist;
use Illuminate\Support\Facades\DB;

class PlaylistsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Playlist::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->input('name');

        $playlist = Playlist::create([
            'name' => $name,
        ]);

        return $playlist;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $playlist_id = $request->input('playlist_id');
        $name = $request->input('name');

        return DB::table('playlists')
            ->where('id', '=', $playlist_id)
            ->update([
                'name' => $name
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $playlist_id = $request->input('playlist_id');

        DB::table('song_playlists')
            ->where('playlist_id', '=', $playlist_id)
            ->delete();

        DB::table('playlists')
            ->where('id', '=', $playlist_id)
            ->delete();

        return true;
    }
}
