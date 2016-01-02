<?php
namespace App\Http\Controllers;

use App\Models\Schema;
use App\Models\Entity;
use App\Http\Helpers;
use Liquid\Records\Record;
use App\Exceptions\ExceptionResolver;
use App\Formatters\ModelFormatter;
use Liquid\Records\Traits\MergeTrait;

class SchemaCompoundController extends CompoundController
{
    use MergeTrait;

    protected $repository;

    protected $endpoints = [
        'node' => ['list', 'show', 'create', 'replace', 'update', 'delete'],
        'link' => ['list', 'show', 'create', 'replace', 'update', 'delete'],
        'entity' => ['apply'],
    ];

    public function __construct(Schema $repository, ModelFormatter $formatter)
    {
        parent::__construct($repository, $formatter);
    }

    public function applyEndpoint($id, $endpoint, $endpoint_id, Entity $entity)
    {
        $this->checkEndpoint($endpoint, __FUNCTION__);
        $schema = $this->repository->where('id', $id)->first();
        if (!$schema)
            throw ExceptionResolver::resolve('not found', "schema with id {$id} not exists");

        $entity = $entity->where('id', $endpoint_id)->first();
        if (!$entity)
            throw ExceptionResolver::resolve('not found', "{$endpoint} with id {$id} not exists");

        $checkpoint = $entity->checkpoints()->where('schema_id', $id)->firstOrNew([
            'schema_id' => $id
        ]);

        $record = Helpers::prepareEntityRecord($entity, $checkpoint);
        $registry = Helpers::buildSchema($schema);
        $registry->process($record);
        $results = array_column(Record::$history, 'result');

        // apply results to entity properties
        $properties = (array)$entity->properties;
        foreach ($results as $result) {
            $this->_conditionedMerge($properties, $result);
        }

        $entity->properties = $properties;
        $entity->save();

        // save record history to checkpoint
        $checkpoint->state = Record::$history;
        $checkpoint->save();
        return $this->response->item($checkpoint, new \App\Formatters\ModelFormatter);
    }
}
