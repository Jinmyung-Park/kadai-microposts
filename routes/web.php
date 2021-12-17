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

Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('signup.get');   //url adredd , 実行するfunction指定　, -> 単純にルーティングの名前指定
Route::post('signup', 'Auth\RegisterController@register')->name('signup.post');             //register blade.php で from送信先が'route' => 'signup.post'に指定されている　つまり　Auth\RegisterController@registerが実行される