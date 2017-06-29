<?php
Route::post('/manuAuth', 'Controller@authenticate');
Route::get('/', function () {
    return view('welcome');
});
 Route::get('/laravel', 'HomeController@laravel');
Route::auth();
Route::post('/postLogin', 'HomeController@attempt');
Route::get('/logout', 'HomeController@logout');
Route::group(['middleware' => 'auth', 'web'], function () {
    Route::get('/home', 'HomeController@index');
	// Contacts
	Route::get('/csv', 'HomeController@csv');
	Route::get('/contact', 'HomeController@contact');
	Route::post('/new-contact', 'HomeController@new_contact');
	Route::post('/selected', 'HomeController@selected');
	// Contact Groups
	Route::get('/contact-group', 'HomeController@contact_group');
	Route::get('/new-contact-group', 'HomeController@new_contact_group');
	Route::get('/view-contact-group/{id}', 'HomeController@view_contact_group');
	Route::get('/show-contact-group', 'HomeController@new_contact_group');
	Route::post('/post-contact-group', 'HomeController@post_contact_group');
	Route::post('/remove-contact', 'HomeController@remove_contact_group');
	// Email Marketing
	Route::get('/select-template', 'HomeController@selecttemplate');
	Route::get('/email-marketing', 'HomeController@emailmarketing');
	Route::post('/send-email', 'HomeController@sendemails'); 
});
