<?php

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

// $app->get('/', function () use ($app) {
// });

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['namespace' => 'App\Http\Controllers'], function ($api) {
    $api->post('/entity', ['as' => 'entity_create', 'uses' => 'EntityController@create']);
    $api->get('/entity', ['as' => 'entity_index', 'uses' => 'EntityController@index']);
    $api->get('/entity/{id:[0-9]+}', ['as' => 'entity_show', 'uses' => 'EntityController@show']);
    $api->put('/entity/{id:[0-9]+}', ['as' => 'entity_update', 'uses' => 'EntityController@update']);
    $api->patch('/entity/{id:[0-9]+}', ['as' => 'entity_change', 'uses' => 'EntityController@change']);
    $api->delete('/entity/{id:[0-9]+}', ['as' => 'entity_delete', 'uses' => 'EntityController@delete']);
    $api->get('/entity/summary', ['as' => 'entity_extract', 'uses' => 'EntityController@extract']);

    $api->post('/schema', ['as' => 'schema_create', 'uses' => 'SchemaController@create']);
    $api->get('/schema', ['as' => 'schema_index', 'uses' => 'SchemaController@index']);
    $api->get('/schema/{id:[0-9]+}', ['as' => 'schema_show', 'uses' => 'SchemaController@show']);
    $api->put('/schema/{id:[0-9]+}', ['as' => 'schema_update', 'uses' => 'SchemaController@update']);
    $api->patch('/schema/{id:[0-9]+}', ['as' => 'schema_change', 'uses' => 'SchemaController@change']);
    $api->delete('/schema/{id:[0-9]+}', ['as' => 'schema_delete', 'uses' => 'SchemaController@delete']);
    $api->get('/schema/summary', ['as' => 'schema_extract', 'uses' => 'SchemaController@extract']);

    $api->post('/policy', ['as' => 'policy_create', 'uses' => 'PolicyController@create']);
    $api->get('/policy', ['as' => 'policy_index', 'uses' => 'PolicyController@index']);
    $api->get('/policy/{id:[0-9]+}', ['as' => 'policy_show', 'uses' => 'PolicyController@show']);
    $api->put('/policy/{id:[0-9]+}', ['as' => 'policy_update', 'uses' => 'PolicyController@update']);
    $api->patch('/policy/{id:[0-9]+}', ['as' => 'policy_change', 'uses' => 'PolicyController@change']);
    $api->delete('/policy/{id:[0-9]+}', ['as' => 'policy_delete', 'uses' => 'PolicyController@delete']);
    $api->get('/policy/summary', ['as' => 'policy_extract', 'uses' => 'PolicyController@extract']);

    $api->post('/privilege', ['as' => 'privilege_create', 'uses' => 'PrivilegeController@create']);
    $api->get('/privilege', ['as' => 'privilege_index', 'uses' => 'PrivilegeController@index']);
    $api->get('/privilege/{id:[0-9]+}', ['as' => 'privilege_show', 'uses' => 'PrivilegeController@show']);
    $api->put('/privilege/{id:[0-9]+}', ['as' => 'privilege_update', 'uses' => 'PrivilegeController@update']);
    $api->patch('/privilege/{id:[0-9]+}', ['as' => 'privilege_change', 'uses' => 'PrivilegeController@change']);
    $api->delete('/privilege/{id:[0-9]+}', ['as' => 'privilege_delete', 'uses' => 'PrivilegeController@delete']);
    $api->get('/privilege/summary', ['as' => 'privilege_extract', 'uses' => 'PrivilegeController@extract']);
});
