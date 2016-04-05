<?php

use GuzzleHttp\Psr7\Request;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Artisan;

class BasicCampaignTest extends TestCase
{
    protected $userId = 'user.5';

    public function testBasicCampaignEventUserInit()
    {
        $accessToken = $this->authorize(['read','edit','execute']);
        $res = $this->client->post('entities', [
            'json' => [
                'data' => [
                    'external_id' => $this->userId
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertArraySubset([
            'external_id' => $this->userId,
            'client_id' => 'policy_testing_account',
        ], (array)$data);

        $res = $this->userInit($accessToken, $data->external_id);
        $this->assertEquals(1, $res->status);
        $entity = $res->entity;
        $this->assertArraySubset([
            'external_id' => $this->userId,
            'client_id' => 'policy_testing_account',
            'login' => 0,
            'posting' => 0,
            'sharing' => 0,
            'rating' => 0,
            'comment' => 0,
            'category' => 'person',
        ], (array)$entity);
    }

    public function testBasicCampaignEventUserLogin()
    {
        $accessToken = $this->authorize(['read','edit','execute']);
        $res = $this->userLogin($accessToken, $this->userId);
        $this->assertEquals(1, $res->status);
        $entity = $res->entity;
        $this->assertEquals(1, $entity->properties->point);
        $res = $this->userLogin($accessToken, $this->userId);
        $this->assertEquals(1, $res->status);
        $entity = $res->entity;
        $this->assertEquals(2, $entity->properties->point);

        $res = $this->userLogin($accessToken, $this->userId);
        $this->assertEquals(1, $res->status);
        $entity = $res->entity;
        $this->assertEquals(3, $entity->properties->point);

        $res = $this->userLogin($accessToken, $this->userId);
        $this->assertEquals(1, $res->status);
        $entity = $res->entity;
        $this->assertEquals(4, $entity->properties->point);
    }

    public function testBasicCampaignEventUserPosting()
    {
        $accessToken = $this->authorize(['read','edit','execute']);
        $res = $this->userPosting($accessToken, $this->userId);
        $this->assertEquals(1, $res->status);
        $entity = $res->entity;
        $this->assertEquals(9, $entity->properties->point);

        $res = $this->userPosting($accessToken, $this->userId);
        $this->assertEquals(1, $res->status);
        $entity = $res->entity;
        $this->assertEquals(14, $entity->properties->point);

        $res = $this->userPosting($accessToken, $this->userId);
        $this->assertEquals(1, $res->status);
        $entity = $res->entity;
        $this->assertEquals(19, $entity->properties->point);

        $res = $this->userPosting($accessToken, $this->userId);
        $this->assertEquals(1, $res->status);
        $entity = $res->entity;
        $this->assertEquals(24, $entity->properties->point);
    }

    public function testBasicCampaignEventUserSharing()
    {
        $accessToken = $this->authorize(['read','edit','execute']);
        $res = $this->userSharing($accessToken, $this->userId);
        $this->assertEquals(1, $res->status);
        $entity = $res->entity;
        $this->assertEquals(27, $entity->properties->point);

        $res = $this->userSharing($accessToken, $this->userId);
        $this->assertEquals(1, $res->status);
        $entity = $res->entity;
        $this->assertEquals(30, $entity->properties->point);

        $res = $this->userSharing($accessToken, $this->userId);
        $this->assertEquals(1, $res->status);
        $entity = $res->entity;
        $this->assertEquals(33, $entity->properties->point);

        $res = $this->userSharing($accessToken, $this->userId);
        $this->assertEquals(1, $res->status);
        $entity = $res->entity;
        $this->assertEquals(36, $entity->properties->point);
    }

    public function testBasicCampaignEventUserComment()
    {
        $accessToken = $this->authorize(['read','edit','execute']);
        $res = $this->userComment($accessToken, $this->userId);
        $this->assertEquals(1, $res->status);
        $entity = $res->entity;
        $this->assertEquals(37, $entity->properties->point);

        $res = $this->userComment($accessToken, $this->userId);
        $this->assertEquals(1, $res->status);
        $entity = $res->entity;
        $this->assertEquals(38, $entity->properties->point);

        $res = $this->userComment($accessToken, $this->userId);
        $this->assertEquals(1, $res->status);
        $entity = $res->entity;
        $this->assertEquals(39, $entity->properties->point);

        $res = $this->userComment($accessToken, $this->userId);
        $this->assertEquals(1, $res->status);
        $entity = $res->entity;
        $this->assertEquals(40, $entity->properties->point);
    }

    public function testBasicCampaignEventUserRating()
    {
        $accessToken = $this->authorize(['read','edit','execute']);
        $res = $this->userRating($accessToken, $this->userId);
        $this->assertEquals(1, $res->status);
        $entity = $res->entity;
        $this->assertEquals(41, $entity->properties->point);

        $res = $this->userRating($accessToken, $this->userId);
        $this->assertEquals(1, $res->status);
        $entity = $res->entity;
        $this->assertEquals(42, $entity->properties->point);

        $res = $this->userRating($accessToken, $this->userId);
        $this->assertEquals(1, $res->status);
        $entity = $res->entity;
        $this->assertEquals(43, $entity->properties->point);

        $res = $this->userRating($accessToken, $this->userId);
        $this->assertEquals(1, $res->status);
        $entity = $res->entity;
        $this->assertEquals(44, $entity->properties->point);
    }

    public function testBasicCampaignUserLevelUp()
    {
        $accessToken = $this->authorize(['read','edit','execute']);
        $res = $this->userPosting($accessToken, $this->userId);
        $this->assertEquals(1, $res->status);
        $entity = $res->entity;
        $this->assertEquals(49, $entity->properties->point);

        $res = $this->userLogin($accessToken, $this->userId);
        $this->assertEquals(1, $res->status);
        $entity = $res->entity;
        $this->assertEquals(50, $entity->properties->point);
        $this->assertEquals(1, $entity->properties->level);
    }

    protected function userInit($accessToken, $target)
    {
        $res = $this->client->post('event/event.user.init', [
            'json' => [
                'target' => $target
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        return json_decode($res->getBody());
    }

    protected function userLogin($accessToken, $target)
    {
        $res = $this->client->post('event/event.user.login', [
            'json' => [
                'target' => $target
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        return json_decode($res->getBody());
    }

    protected function userPosting($accessToken, $target)
    {
        $res = $this->client->post('event/event.user.posting', [
            'json' => [
                'target' => $target
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        return json_decode($res->getBody());
    }

    protected function userSharing($accessToken, $target)
    {
        $res = $this->client->post('event/event.user.sharing', [
            'json' => [
                'target' => $target
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        return json_decode($res->getBody());
    }

    protected function userRating($accessToken, $target)
    {
        $res = $this->client->post('event/event.user.rating', [
            'json' => [
                'target' => $target
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        return json_decode($res->getBody());
    }

    protected function userComment($accessToken, $target)
    {
        $res = $this->client->post('event/event.user.comment', [
            'json' => [
                'target' => $target
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        return json_decode($res->getBody());
    }
}
