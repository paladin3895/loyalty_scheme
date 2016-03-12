<?php

use GuzzleHttp\Psr7\Request;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EndpointTest extends TestCase
{
    use DatabaseTransactions;

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

    public function testOAuthAuthorization()
    {
        $accessToken = $this->authorize();
        $this->assertRegExp('#[0-9a-zA-Z]{40}#', $accessToken);
    }

    public function testOAuthUnauthorized()
    {
      try {
          $this->client->get('entities', [
              'headers' => [
                  'Authorization' => "Bearer invalid_access_token"
              ],
          ]);
      } catch (\Exception $e) {
          $this->assertEquals($e->getCode(), 401);
          return;
      }

      $this->fail();
    }

    public function testOAuthInvalidScope()
    {
        try {
            $accessToken = $this->authorize(['edit']);
            $this->client->get('entities', [
                'headers' => [
                    'Authorization' => "Bearer {$accessToken}"
                ],
            ]);
        } catch (\Exception $e) {
            $this->assertEquals($e->getCode(), 401);
            return;
        }

        $this->fail();
    }

    public function testEntityIndex()
    {
        $accessToken = $this->authorize(['read']);
        $res = $this->client->get('entities', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertTrue(is_array($data));
    }

    public function testEntityShow()
    {
        $accessToken = $this->authorize(['read']);
        $res = $this->client->get('entity/1', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertTrue(is_object($data));
        $this->assertObjectHasAttribute('id', $data);
    }

    // public function testEntityCreate()
    // {
    //
    // }
    //
    // public function testEntityUpdate()
    // {
    //
    // }
    //
    // public function testEntityReplace()
    // {
    //
    // }
    //
    // public function testEntityDelete()
    // {
    //
    // }
}
