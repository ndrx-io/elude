<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| WEB
|--------------------------------------------------------------------------
*/
$app->get('auth/login', ['as' => 'auth.loginForm', 'uses' => 'Auth@loginForm']);
$app->post('auth/login', ['as' => 'auth.login', 'uses' => 'Auth@login']);

$app->group(['middleware' => ['auth', 'csrf']], function () use ($app) {
    $app->get('/', function () use ($app) {

        return view('index');
    });

});

/*
|--------------------------------------------------------------------------
| API
|--------------------------------------------------------------------------
*/
$app->group(['prefix' => 'api/v1', 'middleware' => 'cors'], function () use ($app) {

    $app->post('oauth/access_token', ['as' => 'oauth.login', 'uses' => App\Http\Controllers\Api\Auth::class . '@login']);

    $app->group(['middleware' => 'oauth', 'prefix' => 'api/v1'], function () use ($app) {
        $app->resource('groups', \App\Http\Controllers\Api\Group::class);
        $app->resource('roles', \App\Http\Controllers\Api\Role::class);
        $app->resource('users', \App\Http\Controllers\Api\User::class);
        $app->resource('permissions', \App\Http\Controllers\Api\Permission::class);
    });
});
