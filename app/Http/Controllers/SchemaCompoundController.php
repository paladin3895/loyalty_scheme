<?php
namespace App\Http\Controllers;

use App\Models\Schema;
use App\Models\Entity;
use App\Http\Helper;
use Liquid\Records\Record;

class SchemaCompoundController extends CompoundController
{
    protected $repository;

    protected $endpoints = [
        'node' => ['list', 'show', 'create', 'replace', 'update', 'delete'],
        'link' => ['list', 'show', 'create', 'replace', 'update', 'delete'],
        'entity' => ['apply'],
    ];

    public function __construct(Schema $schema)
    {
        $this->repository = $schema;
    }

    public function applyEndpoint($id, $endpoint, $endpoint_id, Entity $entity)
    {
        $this->checkEndpoint($endpoint, __FUNCTION__);
        $schema = $this->repository->where('id', $id)->first();
        if (!$schema) throw new \Exception('schema not exists');

        $entity = $entity->where('id', $endpoint_id)->first();
        if (!$entity) throw new \Exception('endpoint not exists');
        $data = $entity->toArray()['attributes'];
        $checkpoint = $entity->checkpoint()->where('schema_id', $id)->firstOrNew([
            'schema_id' => $id
        ]);
        $record = new Record((array)$data, (array)$checkpoint->state);
        $registry = Helper::buildSchema($schema);
        $registry->process($record);
        $checkpoint->state = Record::$history;
        $results = array_column(Record::$history, 'result');
        foreach ($results as $result) {
            foreach ($result as $key => $value) {
                $entity->{$key} += $value;
            }
        }
        $entity->save();
        $checkpoint->save();
        return $this->success([$endpoint => $entity]);
    }
}
