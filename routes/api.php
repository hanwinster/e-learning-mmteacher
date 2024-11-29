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

/*
 * Public API Routes
 */

require(base_path().'/routes/api/guest.php');
require(base_path().'/routes/api/auth.php');
require(base_path().'/routes/api/member.php');


Route::get('/getTotalSignups/{start}/{end}', 'Member\DashboardController@getSignupUsersByPeriod')
      ->name('getSignupsByPeriod.api'); //middleware('auth:api')

Route::get('/get-gender-by-date/{start}/{end}', 'Member\DashboardController@getGenderByPeriod')
      ->name('getGenderByPeriod.api'); //middleware('auth:api')

//DiscussionController
Route::post('/add-message', 'DiscussionController@newMessage')->name('add-message.api');
Route::post('/add-message-chat-window', 'DiscussionController@newMessageChatWindow')->name('add-message-chat-window.api');
//Route::get('/test', [DiscussionController::class, 'getTest']); 