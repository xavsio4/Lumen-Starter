<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

//$router->group(['prefix' => 'api'], function () use ($router) {
$router->group(['prefix' => ''], function () use ($router) {
    $router->post('auth/register', 'AuthController@register');
    $router->post('auth/login', 'AuthController@login');
    $router->post('auth/logout', 'AuthController@logout');
    
    $router->get('auth/user', 'UserController@profile');
    $router->get('auth/users/{id}', 'UserController@singleUser');
    $router->get('auth/users', 'UserController@allUsers');
    
    $router->get('v1/bookmarks', 'BookmarkController@index');
    
    $router->post('/password/reset-request', 'ResetPasswordController@generateResetToken');
    $router->post('/password/resetpasword',  'ResetPasswordController@resetPassword' );
    
});