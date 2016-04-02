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

$api->version('v1', [
    'prefix' => 'api/v1',
    'middleware' => 'cors',
    'namespace' => 'App\Http\Controllers'
], function ($api) {

    $api->post('/oauth', ['as' => 'token_authorize', 'uses' => 'OAuthController@authorize']);

    $api->group([
      'middleware' => 'oauth',
    ], function ($api) {

        $api->group(['middleware' => 'oauth:read.entity'], function ($api) {
            $api->get('/entities', ['as' => 'entity_index', 'uses' => 'EntityController@index']);
            $api->get('/entity/{id:[0-9a-zA-Z\.\-\_]+}', ['as' => 'entity_show', 'uses' => 'EntityController@show']);
            // $api->match('head', '/entity', ['as' => 'entity_extract', 'uses' => 'EntityController@extract']);
        });

        $api->group(['middleware' => 'oauth:edit.entity'], function ($api) {
            $api->post('/entities', ['as' => 'entity_create', 'uses' => 'EntityController@create']);
            $api->put('/entity/{id:[0-9a-zA-Z\.\-\_]+}', ['as' => 'entity_replace', 'uses' => 'EntityController@replace']);
            $api->patch('/entity/{id:[0-9a-zA-Z\.\-\_]+}', ['as' => 'entity_update', 'uses' => 'EntityController@update']);
            $api->delete('/entity/{id:[0-9a-zA-Z\.\-\_]+}', ['as' => 'entity_delete', 'uses' => 'EntityController@delete']);
        });

        $api->group(['prefix' => '/entity/{id:[0-9a-zA-Z\.\-\_]+}'], function ($api) {

            $api->group(['middleware' => 'oauth:read.entity'], function ($api) {
                $api->get('/{endpoint:[a-z]+}', [
                    'as' => 'entity_list_endpoint',
                    'uses' => 'EntityCompoundController@indexEndpoint'
                ]);

                $api->get('/{endpoint:[a-z]+}/{endpoint_id:[0-9]+}', [
                    'as' => 'entity_show_endpoint',
                    'uses' => 'EntityCompoundController@showEndpoint'
                ]);
            });

            $api->group(['middleware' => 'oauth:edit.entity'], function ($api) {
                $api->post('/{endpoint:[a-z]+}', [
                    'as' => 'entity_create_endpoint',
                    'uses' => 'EntityCompoundController@createEndpoint'
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
        });

        $api->group(['middleware' => 'oauth:read.schema'], function ($api) {
            $api->get('/schemas', ['as' => 'schema_index', 'uses' => 'SchemaController@index']);
            $api->get('/schema/{id:[0-9a-zA-Z\.\-\_]+}', ['as' => 'schema_show', 'uses' => 'SchemaController@show']);
            // $api->match('head', '/schema', ['as' => 'schema_extract', 'uses' => 'SchemaController@extract']);

            $api->get('/policies', ['as' => 'policy_index', 'uses' => 'ComponentController@getPolicies']);
            $api->get('/rewards', ['as' => 'reward_index', 'uses' => 'ComponentController@getRewards']);
            $api->get('/processors', ['as' => 'processor_index', 'uses' => 'ComponentController@getProcessors']);
        });

        $api->group(['middleware' => 'oauth:edit.schema'], function ($api) {
            $api->post('/schemas', ['as' => 'schema_create', 'uses' => 'SchemaController@create']);
            $api->put('/schema/{id:[0-9a-zA-Z\.\-\_]+}', ['as' => 'schema_replace', 'uses' => 'SchemaController@replace']);
            $api->patch('/schema/{id:[0-9a-zA-Z\.\-\_]+}', ['as' => 'schema_update', 'uses' => 'SchemaController@update']);
            $api->delete('/schema/{id:[0-9a-zA-Z\.\-\_]+}', ['as' => 'schema_delete', 'uses' => 'SchemaController@delete']);
        });

        $api->post('/schema/{id:[0-9a-zA-Z\.\-\_]+}', [
            'middleware' => 'oauth:execute.schema',
            'as' => 'schema_apply',
            'uses' => 'SchemaController@apply'
        ]);

        $api->group(['prefix' => '/schema/{id:[0-9a-zA-Z\.\-\_]+}'], function ($api) {

            $api->group(['middleware' => 'oauth:read.schema'], function ($api) {
                $api->get('/{endpoint:[a-z]+}', [
                    'as' => 'schema_list_endpoint',
                    'uses' => 'SchemaCompoundController@indexEndpoint'
                ]);

                $api->get('/{endpoint:[a-z]+}/{endpoint_id:[0-9]+}', [
                    'as' => 'schema_show_endpoint',
                    'uses' => 'SchemaCompoundController@showEndpoint'
                ]);
            });

            $api->group(['middleware' => 'oauth:edit.schema'], function ($api) {
                $api->post('/{endpoint:[a-z]+}', [
                    'as' => 'schema_create_endpoint',
                    'uses' => 'SchemaCompoundController@createEndpoint'
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

        $api->group(['middleware' => 'oauth:read.event'], function ($api) {
            $api->get('/events', ['as' => 'event_index', 'uses' => 'EventController@index']);
            $api->get('/event/{id:[0-9a-zA-Z\.\-\_]+}', ['as' => 'event_show', 'uses' => 'EventController@show']);
            // $api->match('head', '/event', ['as' => 'event_extract', 'uses' => 'EventController@extract']);
        });

        $api->group(['middleware' => 'oauth:edit.event'], function ($api) {
            $api->post('/events', ['as' => 'event_create', 'uses' => 'EventController@create']);
            $api->put('/event/{id:[0-9a-zA-Z\.\-\_]+}', ['as' => 'event_replace', 'uses' => 'EventController@replace']);
            $api->patch('/event/{id:[0-9a-zA-Z\.\-\_]+}', ['as' => 'event_update', 'uses' => 'EventController@update']);
            $api->delete('/event/{id:[0-9a-zA-Z\.\-\_]+}', ['as' => 'event_delete', 'uses' => 'EventController@delete']);
        });

        $api->post('/event/{id:[0-9a-zA-Z\.\-\_]+}', [
            'middleware' => 'oauth:execute.event',
            'as' => 'event_apply',
            'uses' => 'EventController@apply'
        ]);

        $api->group(['prefix' => '/event/{id:[0-9a-zA-Z\.\-\_]+}'], function ($api) {

            $api->group(['middleware' => 'oauth:read.event'], function ($api) {
                $api->get('/{endpoint:[a-z]+}', [
                    'as' => 'event_list_endpoint',
                    'uses' => 'EventCompoundController@indexEndpoint'
                ]);

                $api->get('/{endpoint:[a-z]+}/{endpoint_id:[0-9]+}', [
                    'as' => 'event_show_endpoint',
                    'uses' => 'EventCompoundController@showEndpoint'
                ]);
            });

            $api->group(['middleware' => 'oauth:edit.event'], function ($api) {
                $api->post('/{endpoint:[a-z]+}', [
                    'as' => 'event_create_endpoint',
                    'uses' => 'EventCompoundController@createEndpoint'
                ]);

                $api->patch('/{endpoint:[a-z]+}/{endpoint_id:[0-9]+}', [
                    'as' => 'event_update_endpoint',
                    'uses' => 'EventCompoundController@updateEndpoint'
                ]);

                $api->put('/{endpoint:[a-z]+}/{endpoint_id:[0-9]+}', [
                    'as' => 'event_replace_endpoint',
                    'uses' => 'EventCompoundController@replaceEndpoint'
                ]);

                $api->delete('/{endpoint:[a-z]+}/{endpoint_id:[0-9]+}', [
                    'as' => 'event_delete_endpoint',
                    'uses' => 'EventCompoundController@deleteEndpoint'
                ]);
            });
        });

    });

});
