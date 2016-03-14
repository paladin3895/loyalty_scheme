<?php

use Illuminate\Support\Facades\Artisan;

class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {

        $app = require __DIR__ . '/../bootstrap/app.php';

        return $app;
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
    }

    protected $client;

    public function __construct()
    {
        $this->client = new GuzzleHttp\Client([
            // Base URI is used with relative requests
            'base_uri' => 'http://liquid.dev/api/v1/'
        ]);
    }

    protected function authorize(array $scopes = ['read'])
    {
        $res = $this->client->post('oauth', [
            'json' => [
                'grant_type' => 'client_credentials',
                'client_id' => 'policy_testing_account',
                'client_secret' => 'policy_testing_secret',
                'scope' => implode(',', $scopes),
            ],
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]);
        $result = json_decode($res->getBody());
        return $result->access_token;
    }
}
