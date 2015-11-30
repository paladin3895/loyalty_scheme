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

        return $this->success($checkpoint->toArray());
    }

    public function applyEntitySchema($entity_id, $schema_id, Schema $liquid_schema)
    {
        $entity = $this->entity->find($entity_id);
        if (!$entity) throw new \Exception('entity not exists');

        $schema = $this->schema->find($schema_id);
        if (!$schema) throw new \Exception('schema not exists');

        $registry = $liquid_schema->build($schema->toArray());
        $registry->process(($entity->toArray())['attributes']);

        return $this->success($this->repository->find($entity_id)->toArray());
    }

    public function getEntityPrivileges($entity_id)
    {
        $entity = $this->entity->find($entity_id);
        if (!$entity) throw new \Exception('entity not exists');

        $privileges = $entity->privileges()->get();

        return $this->success($privileges->toArray());
    }

    protected function success(array $data)
    {
        return [
            'status' => 1,
            "$this->endpoint" => $data
        ];
    }
}
