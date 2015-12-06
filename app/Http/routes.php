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

$app->get('/', ['as' => 'liquid', 'uses' => 'LiquidController@index']);

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['namespace' => 'App\Http\Controllers'], function ($api) {
    $api->post('/entities', ['as' => 'entity_create', 'uses' => 'EntityController@create']);
    $api->get('/entities', ['as' => 'entity_index', 'uses' => 'EntityController@index']);
    $api->get('/entity/{id:[0-9]+}', ['as' => 'entity_show', 'uses' => 'EntityController@show']);
    $api->put('/entity/{id:[0-9]+}', ['as' => 'entity_edit', 'uses' => 'EntityController@edit']);
    $api->patch('/entity/{id:[0-9]+}', ['as' => 'entity_update', 'uses' => 'EntityController@update']);
    $api->delete('/entity/{id:[0-9]+}', ['as' => 'entity_delete', 'uses' => 'EntityController@delete']);
    $api->match('head', '/entity', ['as' => 'entity_extract', 'uses' => 'EntityController@extract']);

    $api->post('/schemas', ['as' => 'schema_create', 'uses' => 'SchemaController@create']);
    $api->get('/schemas', ['as' => 'schema_index', 'uses' => 'SchemaController@index']);
    $api->get('/schema/{id:[0-9]+}', ['as' => 'schema_show', 'uses' => 'SchemaController@show']);
    $api->put('/schema/{id:[0-9]+}', ['as' => 'schema_edit', 'uses' => 'SchemaController@edit']);
    $api->patch('/schema/{id:[0-9]+}', ['as' => 'schema_update', 'uses' => 'SchemaController@update']);
    $api->delete('/schema/{id:[0-9]+}', ['as' => 'schema_delete', 'uses' => 'SchemaController@delete']);
    $api->match('head', '/schema', ['as' => 'schema_extract', 'uses' => 'SchemaController@extract']);

    $api->post('/policies', ['as' => 'policy_create', 'uses' => 'PolicyController@create']);
    $api->get('/policies', ['as' => 'policy_index', 'uses' => 'PolicyController@index']);
    $api->get('/policy/{id:[0-9]+}', ['as' => 'policy_show', 'uses' => 'PolicyController@show']);
    $api->put('/policy/{id:[0-9]+}', ['as' => 'policy_edit', 'uses' => 'PolicyController@edit']);
    $api->patch('/policy/{id:[0-9]+}', ['as' => 'policy_update', 'uses' => 'PolicyController@update']);
    $api->delete('/policy/{id:[0-9]+}', ['as' => 'policy_delete', 'uses' => 'PolicyController@delete']);
    $api->match('head', '/policy', ['as' => 'policy_extract', 'uses' => 'PolicyController@extract']);
    $api->get('/policy/units', ['as' => 'policy_units', 'uses' => 'PolicyController@unitComponents']);
    $api->get('/policy/algorithms', ['as' => 'policy_algorithms', 'uses' => 'PolicyController@algorithmComponents']);

    $api->post('/privileges', ['as' => 'privilege_create', 'uses' => 'PrivilegeController@create']);
    $api->get('/privileges', ['as' => 'privilege_index', 'uses' => 'PrivilegeController@index']);
    $api->get('/privilege/{id:[0-9]+}', ['as' => 'privilege_show', 'uses' => 'PrivilegeController@show']);
    $api->put('/privilege/{id:[0-9]+}', ['as' => 'privilege_edit', 'uses' => 'PrivilegeController@edit']);
    $api->patch('/privilege/{id:[0-9]+}', ['as' => 'privilege_update', 'uses' => 'PrivilegeController@update']);
    $api->delete('/privilege/{id:[0-9]+}', ['as' => 'privilege_delete', 'uses' => 'PrivilegeController@delete']);
    $api->match('head', '/privilege', ['as' => 'privilege_extract', 'uses' => 'PrivilegeController@extract']);

    $api->group(['prefix' => '/entity/{id:[0-9]+}'], function ($api) {
        $api->get('/{endpoint:[a-z]+}', [
            'as' => 'entity_list_endpoint',
            'uses' => 'EntityCompoundController@listEndpoint'
        ]);

        $api->post('/{endpoint:[a-z]+}', [
            'as' => 'entity_create_endpoint',
            'uses' => 'EntityCompoundController@createEndpoint'
        ]);

        $api->get('/{endpoint:[a-z]+}/{endpoint_id:[0-9]+}', [
            'as' => 'entity_get_endpoint',
            'uses' => 'EntityCompoundController@getEndpoint'
        ]);

        $api->put('/{endpoint:[a-z]+}/{endpoint_id:[0-9]+}', [
            'as' => 'entity_update_endpoint',
            'uses' => 'EntityCompoundController@updateEndpoint'
        ]);

        $api->delete('/{endpoint:[a-z]+}/{endpoint_id:[0-9]+}', [
            'as' => 'entity_delete_endpoint',
            'uses' => 'EntityCompoundController@deleteEndpoint'
        ]);
    });

    $api->group(['prefix' => '/schema/{id:[0-9]+}'], function ($api) {
        $api->get('/{endpoint:[a-z]+}', [
            'as' => 'schema_list_endpoint',
            'uses' => 'SchemaCompoundController@listEndpoint'
        ]);

        $api->post('/{endpoint:[a-z]+}', [
            'as' => 'schema_create_endpoint',
            'uses' => 'SchemaCompoundController@createEndpoint'
        ]);

        $api->get('/{endpoint:[a-z]+}/{endpoint_id:[0-9]+}', [
            'as' => 'schema_get_endpoint',
            'uses' => 'SchemaCompoundController@getEndpoint'
        ]);

        $api->put('/{endpoint:[a-z]+}/{endpoint_id:[0-9]+}', [
            'as' => 'schema_update_endpoint',
            'uses' => 'SchemaCompoundController@updateEndpoint'
        ]);

        $api->delete('/{endpoint:[a-z]+}/{endpoint_id:[0-9]+}', [
            'as' => 'schema_delete_endpoint',
            'uses' => 'SchemaCompoundController@deleteEndpoint'
        ]);
    });
});
