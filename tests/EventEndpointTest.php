<?php

use GuzzleHttp\Psr7\Request;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Artisan;

class EventEndpointTest extends TestCase
{
    use DatabaseTransactions;

    public function testEventIndex()
    {
        $accessToken = $this->authorize(['read']);
        $res = $this->client->get('events', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertTrue(is_array($data));
    }

    public function testEventShow()
    {
        $accessToken = $this->authorize(['read']);
        $res = $this->client->get('event/policy_testing_account.event_1', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertTrue(is_object($data));
        $this->assertObjectHasAttribute('id', $data);
    }

    public function testEventCreate()
    {
        $accessToken = $this->authorize(['edit']);
        $event = factory(App\Models\Event::class)->make(['id' => 'event_9999']);
        $res = $this->client->post('events', [
            'json' => [
                'data' => $event->toArray(),
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertObjectHasAttribute('id', $data);
        $this->seeInDatabase('events', ['id' => $data->id]);
        $event = \App\Models\Event::find($data->id);
        $this->assertArraySubset(
            (array)$data, $event->toArray()
        );
    }

    public function testEventUpdate()
    {
        $accessToken = $this->authorize(['edit']);
        $event = factory(App\Models\Event::class)->make();

        $res = $this->client->patch('event/policy_testing_account.event_1', [
            'json' => [
                'data' => $event->toArray(),
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertObjectHasAttribute('id', $data);
        $this->assertEquals($data->id, 'policy_testing_account.event_1');
        $event = \App\Models\Event::find($data->id);
        $this->assertArraySubset(
            (array)$data, $event->toArray()
        );
    }

    public function testEventReplace()
    {
        $accessToken = $this->authorize(['edit']);
        $event = factory(App\Models\Event::class)->make();

        $res = $this->client->put('event/policy_testing_account.event_1', [
            'json' => [
                'data' => $event->toArray(),
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertObjectHasAttribute('id', $data);
        $this->assertEquals($data->id, 'policy_testing_account.event_1');
        $event = \App\Models\Event::find($data->id);
        $this->assertArraySubset(
            (array)$data, $event->toArray()
        );
    }

    public function testEventDelete()
    {
        $accessToken = $this->authorize(['edit']);
        $res = $this->client->delete('event/policy_testing_account.event_1', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertTrue(is_object($data));
        $this->assertObjectHasAttribute('id', $data);
        $this->assertEquals($data->id, 'policy_testing_account.event_1');
        $event = \App\Models\Event::find($data->id);
        $this->assertNull($event);
    }
}
