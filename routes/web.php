<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/jugadorListado', 'App\Http\Controllers\JugadorController@index');
Route::get('/torneoSimular', 'App\Http\Controllers\JugadorController@torneo');

Route::get('/torneoListado', 'App\Http\Controllers\TorneoController@index');
Route::get('/torneoBuscar', 'App\Http\Controllers\TorneoController@search');

Route::get('/', function () {
    return view('welcome');
});
