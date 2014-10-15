<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/*Route::get('/', function()
{
	return View::make('hello');
});*/



Route::resource('uploads', 'UploadController');
Route::post('uploads/uploadFile', 'UploadController@uploadFile');


Route::resource('results', 'ResultPartsController');
Route::get('results/{id}','ResultPartsController@download');

Route::get('/', function()
{
	return View::make('pages.home');
});

Route::get('contact', function()
{
	return View::make('pages.contact');
});

