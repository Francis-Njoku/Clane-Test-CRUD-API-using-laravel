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
/*
Route::get('/', function () {
    return view('welcome');
});
Route::get('/hello', function () {
    //return view('welcome');
    return '<h1>Hello World</h1>';
});

Route::get('/about', function () {
    return view('pages.about');
});

Route::get('/users/{id}', function ($id){
    return 'This is user '.$id;
});
*/
Route::get('/', 'PagesController@index');
Route::get('/about', 'PagesController@about');
Route::get('/services', 'PagesController@services');

Route::resource('posts', 'PostsController');
Route::resource('article', 'ArticleController');

Auth::routes();

Route::get('/dashboard', 'DashboardController@index');
Route::get('/homere', 'PostController@index')->name('Dashboard');
Route::get('/homer', 'PostController@index2')->name('Dashboard');
Route::get('/show-post/{id}', 'ArticleController@show2')->name('Dashboard');
Route::get('/show-post/{id}/rating', 'ArticleController@show2')->name('Dashboard');
Route::post('/article/{id}/rating', 'ArticleController@rating')->name('rating');
Route::get('/article/result/search', 'ArticleController@search')->name('Dashboard');
