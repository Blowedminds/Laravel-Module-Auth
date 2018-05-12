<?php

Route::put('register', 'AuthController@register');

Route::post('login', 'AuthController@login');

Route::get('logout', 'AuthController@logout');

Route::get('is-authenticated', 'AuthController@isAuthenticated');