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
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/threads/{channel}/{thread}', 'ThreadController@show')->name('threads.show');
Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy')->name('threads.destroy');
Route::resource('/threads', 'ThreadController')->except(['show', 'destroy']); //index create store edit update
Route::get('threads/search', 'SearchController@show');
Route::get('/threads/{channel}', 'ThreadController@index')->name('threads.ch.index');

Route::get('/threads/{channel}/{thread}/replies', 'ReplyController@index');
Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store')->name('replies.store');

Route::delete('replies/{reply}', 'ReplyController@destroy')->name('replies.destroy');
Route::patch('replies/{reply}', 'ReplyController@update')->name('replies.update');

Route::post('replies/{reply}/best', 'BestReplyController@store')->name('best-reply.store');

Route::post('/threads/{channel}/{thread}/subscription', 'SubscriptionController@store')->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscription', 'SubscriptionController@destroy')->middleware('auth');

Route::post('/replies/{reply}/favourites', 'FavouritesController@store');
Route::delete('/replies/{reply}/favourites', 'FavouritesController@destroy');

Route::get('/profiles/{user}', 'ProfileController@show')->name('profile');

Route::get('/profiles/{user}/notifications/', 'UserNotificationController@index');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationController@destroy');

Route::get('/api/users', 'Api\UsersController@index');

Route::post('/api/users/{user}/avatar', 'Api\UserAvatarController@store')->name('avatar');

Route::get('/{user}/notices', 'NoticeController@index')->name('notice.index');
Route::get('/{user}/notices/create', 'NoticeController@create')->name('notice.create')->middleware('admin');
Route::post('/{user}/notices', 'NoticeController@store')->name('notice.store')->middleware('admin');

Route::patch('locked-threads/{thread}', 'LockedThreadsController@update')->name('locked-threads.update')->middleware('admin');

Route::get('/admin/users', 'AdminController@show')->name('admin');
Route::patch('/admin/users/{id}/type', 'AdminController@type')->name('user.type');
Route::patch('/admin/users/{id}', 'AdminController@update')->name('user.block');
Route::delete('/admin/users/{id}', 'AdminController@destroy')->name('user.destroy');
