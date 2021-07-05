<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PlaylistsController;
use App\Http\Controllers\SongsController;
use App\Http\Controllers\SongPlaylistsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/songs', [SongsController::class, 'store']);
Route::get('/songs', [SongsController::class, 'index']);
Route::post('/playlists', [PlaylistsController::class, 'store']);
Route::get('/playlists', [PlaylistsController::class, 'index']);
Route::post('/song-playlists', [SongPlaylistsController::class, 'store']);
Route::get('/song-playlists', [SongPlaylistsController::class, 'index']);

?>