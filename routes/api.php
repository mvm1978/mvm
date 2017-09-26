<?php

use Illuminate\Http\Request;

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

Route::get('able-to/v1/questions/get/{id?}', 'AbleTo\QuestionsController@get');
Route::get('able-to/v1/answers/get-type-info', 'AbleTo\AnswersController@getTypeInfo');
Route::get('able-to/v1/answers/get/{id?}', 'AbleTo\AnswersController@get');
Route::get('able-to/v1/quizzes/get/{id?}', 'AbleTo\QuizzesController@get');
Route::post('able-to/v1/quiz-results/create/{id}', 'AbleTo\QuizResultsController@create');
Route::get('able-to/v1/quiz-results/{id}', 'AbleTo\QuizResultsController@getTotals');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
