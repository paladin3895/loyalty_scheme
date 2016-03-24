<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Helpers;
use App\Models\Schema;
use App\Models\Entity;
use App\Formatters\SchemaFormatter;
use App\Exceptions\ExceptionResolver;

use Liquid\Records\Record;
use Liquid\Records\Traits\MergeTrait;

class SchemaController extends SingularController
{
    use MergeTrait;

    protected $endpoint = 'schema';

    public function __construct(Schema $repository, SchemaFormatter $formatter)
    {
        parent::__construct($repository, $formatter);
    }

    public function apply($id, Request $request)
    {
        $schema = $this->repository->find($id);
        if (!$schema) {
            throw ExceptionResolver::resolve('not found', "schema with id {$id} not exists");
        }

        if ($request->has('target')) {
            $entityId = (integer)$request->input('target');
            $entity = Entity::find($entityId);
            if (!$entity) {
                throw ExceptionResolver::resolve('not found', "entity with id {$entityId} not exists");
            }

            return $this->applyToEntity($schema, $entity);

        } elseif ($request->has('data')) {
            $data = (array)$request->input('data');
            $checkpoint = (array)$request->input('checkpoint');

            return $this->applyToData($schema, $data, $checkpoint);
        } else {
            throw ExceptionResolver::resolve('bad request', "request with no data or target entity");
        }
    }

    protected function applyToData(Schema $schema, array $data, array $checkpoint)
    {
        $registry = Helpers::buildSchema($schema);

        $record = new Record($data);
        $registry->process($record);
        return $this->response->array([
            'status' => 1,
            'data' => json_decode(json_encode(Record::history(), JSON_FORCE_OBJECT)),
        ]);
    }

    /*
     * This is special method to apply schema in an entity
     *
     */
    protected function applyToEntity(Schema $schema, Entity $entity)
    {
        $checkpoint = $entity->checkpoints()->where('schema_id', $schema->id)->firstOrNew([
            'schema_id' => $schema->id
        ]);

        $record = Helpers::prepareEntityRecord($entity, $checkpoint);
        $registry = Helpers::buildSchema($schema);
        $registry->process($record);
        $result = Record::history('result');

        // apply results to entity properties
        $properties = (array)$entity->properties;
        $properties = array_merge($properties, $result);

        $entity->properties = $properties;
        $entity->save();

        // save record history to checkpoint
        $checkpoint->state = Record::history('checkpoint');
        $checkpoint->save();
        return $this->response->item($entity, new \App\Formatters\EntityFormatter);
    }
}
