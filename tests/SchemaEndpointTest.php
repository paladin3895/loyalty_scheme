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
    protected static $bucket = [];

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
                'data' => $schema->toArray(),
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertObjectHasAttribute('id', $data);
        $this->seeInDatabase('schemas', ['id' => $data->id]);
        $schema = \App\Models\Schema::find($data->id)->toArray();

        foreach ($schema as $key => $value) {
            if (!isset($data->$key)) continue;
            $this->assertEquals($data->$key, $value);
        }
    }

    public function testSchemaUpdate()
    {
        $accessToken = $this->authorize(['edit']);
        $schema = factory(App\Models\Schema::class)->make();

        $res = $this->client->patch('schema/1', [
            'json' => [
                'data' => $schema->toArray(),
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertObjectHasAttribute('id', $data);
        $this->seeInDatabase('schemas', ['id' => $data->id]);
        $schema = \App\Models\Schema::find($data->id)->toArray();

        foreach ($schema as $key => $value) {
            if (!isset($data->$key)) continue;
            $this->assertEquals($data->$key, $value);
        }
    }

    public function testSchemaReplace()
    {
        $accessToken = $this->authorize(['edit']);
        $schema = factory(App\Models\Schema::class)->make();

        $res = $this->client->put('schema/1', [
            'json' => [
                'data' => $schema->toArray(),
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertObjectHasAttribute('id', $data);
        $this->seeInDatabase('schemas', ['id' => $data->id]);
        $schema = \App\Models\Schema::find($data->id)->toArray();

        foreach ($schema as $key => $value) {
            if (!isset($data->$key)) continue;
            $this->assertEquals($data->$key, $value);
        }
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

    public function testSchemaCreateNode()
    {
        $accessToken = $this->authorize(['edit']);
        $res = $this->client->post('schema/5/nodes', [
            'json' => [
                'data' => [
                    'name' => 'testing_node',
                    'config' => [
                        'class' => 'someclass'
                    ],
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $response = json_decode($res->getBody());
        $this->assertTrue((boolean)$response->status);
        $this->assertTrue(is_object($response->data));
        $this->assertEquals('testing_node', $response->data->name);
        $this->assertEquals(['class' => 'someclass'], (array)$response->data->config);

        self::$bucket['test_node']['node_id'] = $response->data->id;
    }

    public function testSchemaIndexNode()
    {
        $accessToken = $this->authorize(['read']);
        $res = $this->client->get('schema/5/nodes', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $response = json_decode($res->getBody());
        $this->assertTrue((boolean)$response->status);
        $this->assertTrue(is_array($response->data));
    }

    public function testSchemaShowNode()
    {
        $node_id = self::$bucket['test_node']['node_id'];
        $accessToken = $this->authorize(['read']);
        $res = $this->client->get('schema/5/node/' . $node_id, [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $response = json_decode($res->getBody());
        $this->assertTrue((boolean)$response->status);
        $this->assertEquals('testing_node', $response->data->name);
        $this->assertEquals(['class' => 'someclass'], (array)$response->data->config);
    }

    public function testSchemaUpdateNode()
    {
        $node_id = self::$bucket['test_node']['node_id'];
        $accessToken = $this->authorize(['edit']);
        $res = $this->client->patch('schema/5/node/' . $node_id, [
            'json' => [
                'data' => [
                    'name' => 'testing_another_name',
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $response = json_decode($res->getBody());
        $this->assertTrue((boolean)$response->status);
        $this->assertEquals('testing_another_name', $response->data->name);
        $this->assertEquals(['class' => 'someclass'], (array)$response->data->config);
    }

    public function testSchemaReplaceNode()
    {
        $node_id = self::$bucket['test_node']['node_id'];
        $accessToken = $this->authorize(['edit']);
        $res = $this->client->put('schema/5/node/' . $node_id, [
            'json' => [
                'data' => [
                    'name' => 'testing_other_name',
                    'config' => [
                        'class' => 'other_class'
                    ],
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $response = json_decode($res->getBody());
        $this->assertTrue((boolean)$response->status);
        $this->assertEquals('testing_other_name', $response->data->name);
        $this->assertEquals(['class' => 'other_class'], (array)$response->data->config);
    }

    public function testSchemaDeleteNode()
    {
        $node_id = self::$bucket['test_node']['node_id'];
        $accessToken = $this->authorize(['edit']);
        $res = $this->client->delete('schema/5/node/' . $node_id, [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertTrue(is_object($data));
        $this->assertObjectHasAttribute('id', $data);
        $this->assertEquals($data->id, $node_id);
        $node = \App\Models\Node::find($data->id);
        $this->assertNull($node);
    }

    public function testSchemaCreateLink()
    {
        $accessToken = $this->authorize(['edit']);

        $res = $this->client->post('schema/5/nodes', [
            'json' => [
                'data' => [
                    'name' => 'testing_node',
                    'config' => [
                        'class' => 'someclass'
                    ],
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);
        $node_from = json_decode($res->getBody())->data->id;
        self::$bucket['test_link']['node_from'] = $node_from;

        $res = $this->client->post('schema/5/nodes', [
            'json' => [
                'data' => [
                    'name' => 'testing_node',
                    'config' => [
                        'class' => 'someclass'
                    ],
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);
        $node_to = json_decode($res->getBody())->data->id;
        self::$bucket['test_link']['node_to'] = $node_to;

        $res = $this->client->post('schema/5/links', [
            'json' => [
                'data' => [
                    'node_from' => $node_from,
                    'node_to' => $node_to,
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $response = json_decode($res->getBody());
        $this->assertTrue((boolean)$response->status);
        $this->assertTrue(is_object($response->data));
        $this->assertEquals($node_from, $response->data->node_from);
        $this->assertEquals($node_to, $response->data->node_to);
    }

    public function testSchemaIndexLink()
    {
        $accessToken = $this->authorize(['read']);
        $res = $this->client->get('schema/5/links', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $response = json_decode($res->getBody());
        $this->assertTrue((boolean)$response->status);
        $this->assertTrue(is_array($response->data));
    }

    public function testSchemaShowLink()
    {
        $accessToken = $this->authorize(['read']);
        $res = $this->client->get('schema/5/link/1', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $response = json_decode($res->getBody());
        $this->assertTrue((boolean)$response->status);

        $node_from = self::$bucket['test_link']['node_from'];
        $node_to = self::$bucket['test_link']['node_to'];
        $this->assertEquals($node_from, $response->data->node_from);
        $this->assertEquals($node_to, $response->data->node_to);
    }

    public function testSchemaUpdateLink()
    {
        $node_from = self::$bucket['test_link']['node_from'];
        $node_to = self::$bucket['test_link']['node_to'];
        $accessToken = $this->authorize(['edit']);
        $res = $this->client->patch('schema/5/link/1', [
            'json' => [
                'data' => [
                    'node_from' => $node_to,
                    'node_to' => $node_from,
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $response = json_decode($res->getBody());
        $this->assertTrue((boolean)$response->status);
        $this->assertEquals($node_from, $response->data->node_to);
        $this->assertEquals($node_to, $response->data->node_from);
    }

    public function testSchemaReplaceLink()
    {
        $node_from = self::$bucket['test_link']['node_from'];
        $node_to = self::$bucket['test_link']['node_to'];
        $accessToken = $this->authorize(['edit']);
        $res = $this->client->patch('schema/5/link/1', [
            'json' => [
                'data' => [
                    'node_from' => $node_from,
                    'node_to' => $node_to,
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $response = json_decode($res->getBody());
        $this->assertTrue((boolean)$response->status);
        $this->assertEquals($node_from, $response->data->node_from);
        $this->assertEquals($node_to, $response->data->node_to);
    }

    public function testSchemaDeleteLink()
    {
        $accessToken = $this->authorize(['edit']);
        $res = $this->client->delete('schema/5/link/1', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertTrue(is_object($data));
        $this->assertObjectHasAttribute('id', $data);
        $this->assertEquals($data->id, 1);
        $link = \App\Models\Link::find($data->id);
        $this->assertNull($link);
    }

    public function testSchemaApplyData()
    {
        $schema = \App\Models\Schema::find(2);
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

        $response = json_decode($res->getBody());
        $this->assertTrue((boolean)$response->status);
        $this->assertEquals(['point' => '10'], (array)$response->result);

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

        $response = json_decode($res->getBody());
        $this->assertTrue((boolean)$response->status);
        $this->assertEquals(['point' => '20'], (array)$response->result);
    }

    public function testSchemaApplyTarget()
    {
        $schema = \App\Models\Schema::find(3);
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

        $accessToken = $this->authorize(['edit', 'execute']);
        $res = $this->client->post('schema/3', [
            'json' => [
                'target' => 2,
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $response = json_decode($res->getBody());
        $this->assertTrue((boolean)$response->status);
        $this->assertEquals([], (array)$response->result);

        $this->client->patch('entity/2', [
            'json' => [
                'data' => [
                    'name' => 'Come-Stay',
                ]
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $res = $this->client->post('schema/3', [
            'json' => [
                'target' => 2,
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $response = json_decode($res->getBody());
        $this->assertTrue((boolean)$response->status);
        $this->assertEquals(['point' => '10'], (array)$response->result);

        $this->client->patch('entity/2', [
            'json' => [
                'data' => [
                    'name' => 'Come-Stay with David',
                ]
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $res = $this->client->post('schema/3', [
            'json' => [
                'target' => 2,
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $response = json_decode($res->getBody());
        $this->assertTrue((boolean)$response->status);
        $this->assertEquals(['point' => '20'], (array)$response->result);
    }
}
