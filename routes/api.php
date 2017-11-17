<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'Cors'], function() {

    Route::get('library/v1/genre', 'Library\GenreController@fetch');
    Route::get('library/v1/genre/dropdown', 'Library\GenreController@getDropdown');
    Route::post('library/v1/genre', 'Library\GenreController@upload');
    Route::patch('library/v1/genre/{id}', 'Library\GenreController@patch');

    Route::get('library/v1/author', 'Library\AuthorController@fetch');
    Route::get('library/v1/author/dropdown', 'Library\AuthorController@getDropdown');
    Route::post('library/v1/author', 'Library\AuthorController@upload');
    Route::patch('library/v1/author/{id}', 'Library\AuthorController@patch');

    Route::get('library/v1/book', 'Library\BookController@fetch');
    Route::post('library/v1/book', 'Library\BookController@upload');
    Route::patch('library/v1/book/{id}', 'Library\BookController@patch');
});
