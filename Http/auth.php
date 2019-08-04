<?php

Route::post('login', 'AuthController@login');

Route::post('reset-password', 'AuthController@resetPassword');

Route::get('logout', 'AuthController@logout');

Route::get('is-authenticated', 'AuthController@isAuthenticated');
