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
        $res = $this->client->get('event/1', [
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
        $event = factory(App\Models\Event::class)->make();
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

        $res = $this->client->patch('event/1', [
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
        $this->assertEquals($data->id, 1);
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

        $res = $this->client->put('event/1', [
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
        $this->assertEquals($data->id, 1);
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
        $res = $this->client->delete('event/1', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertTrue(is_object($data));
        $this->assertObjectHasAttribute('id', $data);
        $this->assertEquals($data->id, 1);
        $event = \App\Models\Event::find($data->id);
        $this->assertNull($event);
    }

    public function testEventCreateSubscriber()
    {
        $accessToken = $this->authorize(['edit']);
        $res = $this->client->post('event/2/subscribers', [
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

        $res = $this->client->post('event/2/subscribers', [
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
        $res = $this->client->get('event/2/subscribers', [
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
        $res = $this->client->get('event/2/subscriber/1', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $result = json_decode($res->getBody());
        $this->assertTrue((boolean)$result->status);
        $this->assertTrue(is_object($result->data));
        $this->assertEquals(5, $result->data->schema_id);
        $this->assertEquals('2', $result->data->event_id);
        $this->assertEquals(1, $result->data->priority);
    }

    public function testEventUpdateSubscriber()
    {
        $accessToken = $this->authorize(['edit']);
        $res = $this->client->patch('event/2/subscriber/1', [
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
        $this->assertEquals('2', $result->data->event_id);
        $this->assertEquals(10, $result->data->priority);
    }

    public function testEventReplaceSubscriber()
    {
        $accessToken = $this->authorize(['edit']);
        $res = $this->client->put('event/2/subscriber/1', [
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
        $this->assertEquals('2', $result->data->event_id);
        $this->assertEquals(100, $result->data->priority);
    }

    public function testEventDeleteSubscriber()
    {
        $accessToken = $this->authorize(['edit']);
        $res = $this->client->delete('event/2/subscriber/1', [
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
        $schema_1 = \App\Models\Schema::find(9);
        $schema_1->nodes()->create([
            'config' => [
                'class' => 'this will fail',
            ],
        ]);

        $schema_2 = \App\Models\Schema::find(10);
        $schema_2->nodes()->create([
            'policies' => [
                [
                    'class' => 'Liquid\Processors\Units\Policies\CheckValuePolicy',
                    'attribute' => 'name',
                    'condition' => 'regex:#.*#',
                    ]
            ],
            'rewards' => [
                [
                    'class' => 'Liquid\Processors\Units\Rewards\AddValueReward',
                    'attribute' => 'point',
                    'value' => '${point} + 10',
                    ]
            ],
            'config' => [
                'class' => 'Liquid\Processors\PolicyProcessor',
            ],
        ]);

        $accessToken = $this->authorize(['edit,execute']);

        $this->client->post('event/5/subscribers', [
            'json' => [
                'data' => [
                    'schema_id' => 9,
                    'priority' => 1,
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $this->client->post('event/5/subscribers', [
            'json' => [
                'data' => [
                    'schema_id' => 10,
                    'priority' => 2,
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $this->client->patch('entity/5', [
            'json' => [
                'data' => [
                    'category' => 'person'
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $res = $this->client->post('event/5', [
            'json' => [
                'target' => 5
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $response = json_decode($res->getBody());
        $this->assertTrue(((boolean)$response->status));
        $this->assertEquals(1, count($response->errors));
        $this->assertEquals(1, count($response->results));
    }
}
