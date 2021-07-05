<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Song;

class SongsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Song::all();
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
        $path = $request->file('file')->store('public');

        $filename = explode('/', $path)[1];



        $song = Song::create([
            'title' => $title,
            'artist' => $artist,
            'album' => $album,
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
