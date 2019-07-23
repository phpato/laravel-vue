<?php

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

Route::get('/', function () {
    return view('contenido.contenido');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/categoria', 'CategoriaController@index')->name('categoria');
Route::post('/categoria', 'CategoriaController@store');
Route::put('/categoria/{id}', 'CategoriaController@update');
Route::put('/categoria/activar/{id}', 'CategoriaController@activar');
Route::put('/categoria/desactivar/{id}', 'CategoriaController@desactivar');