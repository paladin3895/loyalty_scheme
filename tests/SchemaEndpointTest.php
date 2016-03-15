<?php

use GuzzleHttp\Psr7\Request;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\BaseModel;
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
}
