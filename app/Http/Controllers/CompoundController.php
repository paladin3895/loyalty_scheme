<?php
namespace App\Http\Controllers;

use App\Models\Entity;
use App\Models\Schema;

class CompoundController
{
    protected $entity;

    protected $schema;

    public function __construct(
        Entity $entity,
        Schema $schema
    ) {
        $this->entity = $entity;
        $this->schema = $schema;
    }

    public function getEntitySchemaCheckpoint($entity_id, $schema_id)
    {
        $entity = $this->entity->find($entity_id);
        if (!$entity) throw new \Exception('entity not exists');

        $checkpoint = $entity->checkpoints()->where('schema_id', $schema_id)->first();
        if (!$checkpoint) throw new \Exception('no checkpoint for this schema');
        return $this->success(['checkpoint' => $checkpoint->toArray()]);
    }

    public function applyEntitySchema($entity_id, $schema_id, \Liquid\Schema $liquid_schema)
    {
        $entity = $this->entity->find($entity_id);
        if (!$entity) throw new \Exception('entity not exists');
        $entity = $entity->toArray();

        $schema = $this->schema->find($schema_id);
        if (!$schema) throw new \Exception('schema not exists');

        $registry = $liquid_schema->build($schema->toArray());
        $registry->process($entity['attributes']);

        return $this->success(['entity' => $this->repository->find($entity_id)->toArray()]);
    }

    public function getEntityPrivileges($entity_id)
    {
        $entity = $this->entity->find($entity_id);
        if (!$entity) throw new \Exception('entity not exists');

        $privileges = $entity->privileges()->get();

        return $this->success(['privileges' => $privileges->toArray()]);
    }

    public function success(array $data)
    {
        $results = [];
        $results['status'] = 1;
        foreach ($data as $key => $value) {
            $results[$key] = $value;
        }
        return $results;
    }
}
