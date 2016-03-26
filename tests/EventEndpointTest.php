<?php

use GuzzleHttp\Psr7\Request;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Artisan;

class EventEndpointTest extends TestCase
{

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
        $this->assertEquals(
            (array)$data->content, $event->content
        );
        $this->assertEquals(
            (array)$data->condition, $event->condition
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
        $this->assertEquals(
            (array)$data->content, $event->content
        );
        $this->assertEquals(
            (array)$data->condition, $event->condition
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
        $this->assertEquals(
            (array)$data->content, $event->content
        );
        $this->assertEquals(
            (array)$data->condition, $event->condition
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

    public function testEventCreateSubscriber()
    {
        $accessToken = $this->authorize(['edit']);
        $res = $this->client->post('event/policy_testing_account.event_2/subscribers', [
            'json' => [
                'data' => [
                    'schema_id' => 5,
                    'priority' => 1,
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $result = json_decode($res->getBody());
        $this->assertTrue((boolean)$result->status);
        $this->assertTrue(is_object($result->data));
        $this->assertEquals(5, $result->data->schema_id);
        $this->assertEquals(1, $result->data->priority);

        $res = $this->client->post('event/policy_testing_account.event_2/subscribers', [
            'json' => [
                'data' => [
                    'schema_id' => 5,
                    'priority' => 5,
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $result = json_decode($res->getBody());
        $this->assertTrue((boolean)$result->status);
        $this->assertTrue(is_object($result->data));
        $this->assertEquals(5, $result->data->schema_id);
        $this->assertEquals(5, $result->data->priority);
    }

    public function testEventIndexSubscriber()
    {
        $accessToken = $this->authorize(['read']);
        $res = $this->client->get('event/policy_testing_account.event_2/subscribers', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $result = json_decode($res->getBody());
        $this->assertTrue((boolean)$result->status);
        $this->assertTrue(is_array($result->data));
    }

    public function testEventShowSubscriber()
    {
        $accessToken = $this->authorize(['read']);
        $res = $this->client->get('event/policy_testing_account.event_2/subscriber/1', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $result = json_decode($res->getBody());
        $this->assertTrue((boolean)$result->status);
        $this->assertTrue(is_object($result->data));
        $this->assertEquals(5, $result->data->schema_id);
        $this->assertEquals('policy_testing_account.event_2', $result->data->event_id);
        $this->assertEquals(1, $result->data->priority);
    }

    public function testEventUpdateSubscriber()
    {
        $accessToken = $this->authorize(['edit']);
        $res = $this->client->patch('event/policy_testing_account.event_2/subscriber/1', [
            'json' => [
                'data' => [
                    'priority' => 10,
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $result = json_decode($res->getBody());
        $this->assertTrue((boolean)$result->status);
        $this->assertTrue(is_object($result->data));
        $this->assertEquals(5, $result->data->schema_id);
        $this->assertEquals('policy_testing_account.event_2', $result->data->event_id);
        $this->assertEquals(10, $result->data->priority);
    }

    public function testEventReplaceSubscriber()
    {
        $accessToken = $this->authorize(['edit']);
        $res = $this->client->put('event/policy_testing_account.event_2/subscriber/1', [
            'json' => [
                'data' => [
                    'schema_id' => 4,
                    'priority' => 100,
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $result = json_decode($res->getBody());
        $this->assertTrue((boolean)$result->status);
        $this->assertTrue(is_object($result->data));
        $this->assertEquals(4, $result->data->schema_id);
        $this->assertEquals('policy_testing_account.event_2', $result->data->event_id);
        $this->assertEquals(100, $result->data->priority);
    }

    public function testEventDeleteSubscriber()
    {
        $accessToken = $this->authorize(['edit']);
        $res = $this->client->delete('event/policy_testing_account.event_2/subscriber/1', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertTrue(is_object($data));
        $this->assertObjectHasAttribute('id', $data);
        $this->assertEquals($data->id, 1);
        $event = \App\Models\Subscriber::find($data->id);
        $this->assertNull($event);
    }

    public function testEventApply()
    {
        // $accessToken = $this->authorize(['execute']);
        // $res = $this->client->post('event/policy_testing_account.event_2', [
        //     'json' => [
        //         'target' => 4
        //     ],
        //     'headers' => [
        //         'Authorization' => "Bearer {$accessToken}",
        //         'Accept' => 'application/json',
        //     ],
        // ]);
    }
}
