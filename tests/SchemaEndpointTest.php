<?php

use GuzzleHttp\Psr7\Request;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\BaseModel;
use App\Models\Schema;
use Illuminate\Support\Facades\Artisan;

class SchemaEndpointTest extends TestCase
{
    use DatabaseTransactions;

    public function testSchemaIndex()
    {
        $accessToken = $this->authorize(['read']);
        $res = $this->client->get('schemas', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertTrue(is_array($data));
    }

    public function testSchemaShow()
    {
        $accessToken = $this->authorize(['read']);
        $res = $this->client->get('schema/1', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertTrue(is_object($data));
        $this->assertObjectHasAttribute('id', $data);
    }

    public function testSchemaCreate()
    {
        $accessToken = $this->authorize(['edit']);
        $schema = factory(App\Models\Schema::class)->make();

        $res = $this->client->post('schemas', [
            'json' => [
                'data' => $schema->toArray()['attributes'],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertObjectHasAttribute('id', $data);
        $this->seeInDatabase('schemas', ['id' => $data->id]);
        $schema = \App\Models\Schema::find($data->id);
        $this->assertArraySubset(
            (array)$data, $schema->toArray()
        );
    }

    public function testSchemaUpdate()
    {
        $accessToken = $this->authorize(['edit']);
        $schema = factory(App\Models\Schema::class)->make();

        $res = $this->client->patch('schema/1', [
            'json' => [
                'data' => $schema->toArray()['attributes'],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertObjectHasAttribute('id', $data);
        $this->seeInDatabase('schemas', ['id' => 1]);
        $schema = \App\Models\Schema::find($data->id);
        $this->assertArraySubset(
            (array)$data, $schema->toArray()
        );
    }

    public function testSchemaReplace()
    {
        $accessToken = $this->authorize(['edit']);
        $schema = factory(App\Models\Schema::class)->make();

        $res = $this->client->put('schema/1', [
            'json' => [
                'data' => $schema->toArray()['attributes'],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertObjectHasAttribute('id', $data);
        $this->seeInDatabase('schemas', ['id' => 1]);
        $schema = \App\Models\Schema::find($data->id);
        $this->assertArraySubset(
            (array)$data, $schema->toArray()
        );
    }

    public function testSchemaDelete()
    {
        $accessToken = $this->authorize(['edit']);
        $res = $this->client->delete('schema/1', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertTrue(is_object($data));
        $this->assertObjectHasAttribute('id', $data);
        $this->assertEquals($data->id, 1);
        $schema = \App\Models\Schema::find($data->id);
        $this->assertNull($schema);
    }

    public function testSchemaApplyData()
    {
        $schema = Schema::find(2);
        $node_1 = $schema->nodes()->create([
            'policies' => [
                [
                    'class' => 'Liquid\Processors\Units\Policies\CheckValuePolicy',
                    'attribute' => 'name',
                    'condition' => 'regex:#Come\-Stay#',
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

        $node_2 = $schema->nodes()->create([
            'policies' => [
                [
                    'class' => 'Liquid\Processors\Units\Policies\CheckValuePolicy',
                    'attribute' => 'name',
                    'condition' => 'regex:#David#',
                    ]
            ],
            'rewards' => [
                [
                    'class' => 'Liquid\Processors\Units\Rewards\AddValueReward',
                    'attribute' => 'point',
                    'value' => '2 * ${point}',
                    ]
            ],
            'config' => [
                'class' => 'Liquid\Processors\PolicyProcessor',
            ],
        ]);

        $schema->links()->create([
            'node_from' => $node_1->id,
            'node_to' => $node_2->id,
        ]);

        $accessToken = $this->authorize(['execute']);
        $res = $this->client->post('schema/2', [
            'json' => [
                'data' => [
                    'name' => 'Come-Stay',
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $result = json_decode($res->getBody());
        $this->assertTrue((boolean)$result->status);
        $this->assertEquals(['point' => '10'], (array)$result->data->result);

        $res = $this->client->post('schema/2', [
            'json' => [
                'data' => [
                    'name' => 'Come-Stay with David',
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $result = json_decode($res->getBody());
        $this->assertTrue((boolean)$result->status);
        $this->assertEquals(['point' => '20'], (array)$result->data->result);
    }

    public function testSchemaApplyTarget()
    {

    }
}
