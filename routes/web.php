<?php

// user routes
Route::post('login', 'usersController@login');
Route::post('signup', 'usersController@create');
Route::post('/user', 'usersController@login');
Route::post('uploadPhoto', 'usersController@photo');

// metting routes
Route::post('addMeeting', 'meetingController@define');
Route::post('sendInvitation', 'meetingController@send');
Route::post('answerInvitation', 'meetingController@answer');

// general routes
Route::get('uploadPhoto', function () {
    return view('login');
});

Route::get('sendInvitation', function () {
    return view('login');
});

Route::get('answerInvitation', function () {
    return view('login');
});

Route::get('/', function () {
    return view('login');
});

Route::get('/users', function () {
    return view('login');
});

Route::get('/signup', function () {
    return view('signup');
});

Route::get('/login', function () {
    return view('login');
});
