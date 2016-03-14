<?php

use GuzzleHttp\Psr7\Request;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Artisan;

class OAuthEndpointTest extends TestCase
{
    use DatabaseTransactions;

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
}
