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
$app->get('/diagram', ['as' => 'liquid_diagram', 'uses' => 'LiquidController@diagram']);

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['prefix' => 'api/v1', 'namespace' => 'App\Http\Controllers'], function ($api) {
    $api->post('/oauth', ['as' => 'token_authorize', 'uses' => 'OAuthController@authorize']);

    $api->group([], function ($api) {
        $api->post('/entities', ['as' => 'entity_create', 'uses' => 'EntityController@create']);
        $api->get('/entities', ['as' => 'entity_index', 'uses' => 'EntityController@index']);
        $api->get('/entity/{id:[0-9]+}', ['as' => 'entity_show', 'uses' => 'EntityController@show']);
        $api->put('/entity/{id:[0-9]+}', ['as' => 'entity_replace', 'uses' => 'EntityController@replace']);
        $api->patch('/entity/{id:[0-9]+}', ['as' => 'entity_update', 'uses' => 'EntityController@update']);
        $api->delete('/entity/{id:[0-9]+}', ['as' => 'entity_delete', 'uses' => 'EntityController@delete']);
        // $api->match('head', '/entity', ['as' => 'entity_extract', 'uses' => 'EntityController@extract']);

        $api->post('/schemas', ['as' => 'schema_create', 'uses' => 'SchemaController@create']);
        $api->get('/schemas', ['as' => 'schema_index', 'uses' => 'SchemaController@index']);
        $api->get('/schema/{id:[0-9]+}', ['as' => 'schema_show', 'uses' => 'SchemaController@show']);
        $api->put('/schema/{id:[0-9]+}', ['as' => 'schema_replace', 'uses' => 'SchemaController@replace']);
        $api->patch('/schema/{id:[0-9]+}', ['as' => 'schema_update', 'uses' => 'SchemaController@update']);
        $api->delete('/schema/{id:[0-9]+}', ['as' => 'schema_delete', 'uses' => 'SchemaController@delete']);
        $api->post('/schema/{id:[0-9]+}', ['as' => 'schema_apply', 'uses' => 'SchemaController@apply']);
        // $api->match('head', '/schema', ['as' => 'schema_extract', 'uses' => 'SchemaController@extract']);

        $api->get('/policies', ['as' => 'policy_index', 'uses' => 'ComponentController@getPolicies']);

        $api->get('/rewards', ['as' => 'reward_index', 'uses' => 'ComponentController@getRewards']);

        $api->get('/processors', ['as' => 'processor_index', 'uses' => 'ComponentController@getProcessors']);

        $api->group(['prefix' => '/entity/{id:[0-9]+}'], function ($api) {
            $api->get('/{endpoint:[a-z]+}', [
                'as' => 'entity_list_endpoint',
                'uses' => 'EntityCompoundController@indexEndpoint'
            ]);

            $api->post('/{endpoint:[a-z]+}', [
                'as' => 'entity_create_endpoint',
                'uses' => 'EntityCompoundController@createEndpoint'
            ]);

            $api->get('/{endpoint:[a-z]+}/{endpoint_id:[0-9]+}', [
                'as' => 'entity_show_endpoint',
                'uses' => 'EntityCompoundController@showEndpoint'
            ]);

            $api->patch('/{endpoint:[a-z]+}/{endpoint_id:[0-9]+}', [
                'as' => 'entity_update_endpoint',
                'uses' => 'EntityCompoundController@updateEndpoint'
            ]);

            $api->put('/{endpoint:[a-z]+}/{endpoint_id:[0-9]+}', [
                'as' => 'entity_replace_endpoint',
                'uses' => 'EntityCompoundController@replaceEndpoint'
            ]);

            $api->delete('/{endpoint:[a-z]+}/{endpoint_id:[0-9]+}', [
                'as' => 'entity_delete_endpoint',
                'uses' => 'EntityCompoundController@deleteEndpoint'
            ]);
        });

        $api->group(['prefix' => '/schema/{id:[0-9]+}'], function ($api) {
            $api->get('/{endpoint:[a-z]+}', [
                'as' => 'schema_list_endpoint',
                'uses' => 'SchemaCompoundController@indexEndpoint'
            ]);

            $api->post('/{endpoint:[a-z]+}', [
                'as' => 'schema_create_endpoint',
                'uses' => 'SchemaCompoundController@createEndpoint'
            ]);

            $api->post('/{endpoint:[a-z]+}/{endpoint_id:[0-9]+}', [
                'as' => 'schema_apply_endpoint',
                'uses' => 'SchemaCompoundController@applyEndpoint',
            ]);

            $api->get('/{endpoint:[a-z]+}/{endpoint_id:[0-9]+}', [
                'as' => 'schema_show_endpoint',
                'uses' => 'SchemaCompoundController@showEndpoint'
            ]);

            $api->patch('/{endpoint:[a-z]+}/{endpoint_id:[0-9]+}', [
                'as' => 'schema_update_endpoint',
                'uses' => 'SchemaCompoundController@updateEndpoint'
            ]);

            $api->put('/{endpoint:[a-z]+}/{endpoint_id:[0-9]+}', [
                'as' => 'schema_replace_endpoint',
                'uses' => 'SchemaCompoundController@replaceEndpoint'
            ]);

            $api->delete('/{endpoint:[a-z]+}/{endpoint_id:[0-9]+}', [
                'as' => 'schema_delete_endpoint',
                'uses' => 'SchemaCompoundController@deleteEndpoint'
            ]);
        });

    });
});
