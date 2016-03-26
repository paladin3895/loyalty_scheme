<?php

use GuzzleHttp\Psr7\Request;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Artisan;

class EntityEndpointTest extends TestCase
{
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

    public function testEntityCreate()
    {
        $accessToken = $this->authorize(['edit']);
        $person = factory(App\Models\Entity::class, 'person')->make();

        $res = $this->client->post('entities', [
            'json' => [
                'data' => $person->toArray(),
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertObjectHasAttribute('id', $data);
        $this->seeInDatabase('entities', ['id' => $data->id]);
        $entity = \App\Models\Entity::find($data->id);
        $this->assertArraySubset(
            (array)$data, $entity->toArray()
        );
    }

    public function testEntityUpdate()
    {
        $accessToken = $this->authorize(['edit']);
        $person = factory(App\Models\Entity::class, 'person')->make();

        $res = $this->client->patch('entity/1', [
            'json' => [
                'data' => $person->toArray(),
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertObjectHasAttribute('id', $data);
        $this->seeInDatabase('entities', ['id' => 1]);
        $entity = \App\Models\Entity::find($data->id);
        $this->assertArraySubset(
            (array)$data, $entity->toArray()
        );
    }

    public function testEntityReplace()
    {
        $accessToken = $this->authorize(['edit']);
        $person = factory(App\Models\Entity::class, 'person')->make();

        $res = $this->client->put('entity/1', [
            'json' => [
                'data' => $person->toArray(),
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertObjectHasAttribute('id', $data);
        $this->seeInDatabase('entities', ['id' => 1]);
        $entity = \App\Models\Entity::find($data->id);
        $this->assertArraySubset(
            (array)$data, $entity->toArray()
        );
    }

    public function testEntityDelete()
    {
        $accessToken = $this->authorize(['edit']);
        $res = $this->client->delete('entity/1', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($res->getBody())->data;
        $this->assertTrue(is_object($data));
        $this->assertObjectHasAttribute('id', $data);
        $this->assertEquals($data->id, 1);
        $entity = \App\Models\Entity::find($data->id);
        $this->assertNull($entity);
    }

    public function testEntityIndexCheckpoint()
    {
        $accessToken = $this->authorize(['read']);
        $res = $this->client->get('entity/2/checkpoints', [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $result = json_decode($res->getBody());
        $this->assertTrue((boolean)$result->status);
        $this->assertTrue(is_array($result->data));
    }
}
