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
        
        $checkpoint = $entity->checkpoint()->where('schema_id', $id)->firstOrNew([
            'schema_id' => $id
        ]);

        $record = Helper::prepareEntityRecord($entity, $checkpoint);
        $registry = Helper::buildSchema($schema);
        $registry->process($record);
        $results = array_column(Record::$history, 'result');

        // apply results to entity properties
        $properties = (array)$entity->properties;
        foreach ($results as $result) {
            foreach ($result as $key => $value) {
                if (!isset($properties[$key])) $properties[$key] = null;
                if (is_numeric($value)) {
                    $properties[$key] += $value;
                } elseif (is_array($value)) {
                    $properties[$key] = array_merge((array)$properties[$key], $value);
                }
            }
        }

        $entity->properties = $properties;
        $entity->save();

        // save record history to checkpoint
        $checkpoint->state = Record::$history;
        $checkpoint->save();
        return $this->success([$endpoint => $entity]);
    }
}
