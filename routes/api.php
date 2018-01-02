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

    Route::get('virtual-library/v1/genre', 'Library\GenreController@fetch');
    Route::get('virtual-library/v1/genre/dropdown', 'Library\GenreController@getDropdown');
    Route::post('virtual-library/v1/genre', 'Library\GenreController@upload');
    Route::patch('virtual-library/v1/genre/{field}/{id}', 'Library\GenreController@patch');
    Route::post('virtual-library/v1/genre/create-report-pdf', 'Library\GenreController@createReportPDF');
    Route::get('virtual-library/v1/genre/download-report-pdf/{fileName}',
            'Library\GenreController@downloadReportPDF');
    Route::get('virtual-library/v1/genre/count', 'Library\GenreController@getCount');

    Route::get('virtual-library/v1/author', 'Library\AuthorController@fetch');
    Route::get('virtual-library/v1/author/dropdown', 'Library\AuthorController@getDropdown');
    Route::post('virtual-library/v1/author', 'Library\AuthorController@upload');
    // have to use POST method rather than PUT or PATCH when updating a picture
    Route::post('virtual-library/v1/author/{field}/{id}', 'Library\AuthorController@patch');
    Route::patch('virtual-library/v1/author/{field}/{id}', 'Library\AuthorController@patch');
    Route::post('virtual-library/v1/author/create-report-pdf', 'Library\AuthorController@createReportPDF');
    Route::get('virtual-library/v1/author/download-report-pdf/{fileName}',
            'Library\AuthorController@downloadReportPDF');
    Route::get('virtual-library/v1/author/count', 'Library\AuthorController@getCount');

    Route::get('virtual-library/v1/book', 'Library\BookController@fetch');
    Route::get('virtual-library/v1/book/download/{fileName}', 'Library\BookController@download');
    Route::get('virtual-library/v1/book/top/{amount}', 'Library\BookController@getTop');
    Route::post('virtual-library/v1/book', 'Library\BookController@upload');
    Route::post('virtual-library/v1/book/vote/{id}', 'Library\BookController@vote');
    // have to use POST method rather than PUT or PATCH when updating a picture
    Route::post('virtual-library/v1/book/{field}/{id}', 'Library\BookController@patch');
    Route::patch('virtual-library/v1/book/{field}/{id}', 'Library\BookController@patch');
    Route::get('virtual-library/v1/book/chart', 'Library\BookController@chart');
    Route::post('virtual-library/v1/book/create-report-pdf', 'Library\BookController@createReportPDF');
    Route::get('virtual-library/v1/book/download-report-pdf/{fileName}',
            'Library\BookController@downloadReportPDF');
    Route::get('virtual-library/v1/book/create-pdf/{title}/{author}',
            'Library\BookController@createPDF');

    Route::get('virtual-library/v1/type/dropdown', 'Library\TypeController@getDropdown');
    Route::get('virtual-library/v1/type/count', 'Library\TypeController@getCount');
});
