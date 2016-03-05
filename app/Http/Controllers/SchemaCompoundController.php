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
        'node' => [
            'methods' => [
                'index', 'show', 'create', 'replace', 'update', 'delete'
            ],
            'controller' => 'App\Http\Controllers\NodeController',
            'formatter' => 'App\Formatters\NodeFormatter',
        ],
        'link' => [
            'methods' => [
                'index', 'show', 'create', 'replace', 'update', 'delete'
            ],
            'controller' => 'App\Http\Controllers\LinkController',
            'formatter' => 'App\Formatters\LinkFormatter',
        ],
    ];

    public function __construct(Schema $repository, ModelFormatter $formatter)
    {
        parent::__construct($repository, $formatter);

        $this->middleware('oauth');

        $this->scopes('read.schema', ['only' => ['index', 'extract', 'show']]);
        $this->scopes('edit.schema', ['only' => ['create', 'update', 'delete']]);
        $this->scopes('execute.schema', ['only' => ['apply']]);
    }

    /*
     * This is special method to apply schema in an entity
     *
     */
    public function applyEndpoint($id, $endpoint, $endpoint_id, Entity $entity)
    {
        $id = (int)$id;
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
            $properties = $this->_conditionedMerge($properties, $result);
        }

        $entity->properties = $properties;
        $entity->save();

        // save record history to checkpoint
        $checkpoint->state = Record::$history['checkpoint'];
        $checkpoint->save();
        return $this->response->item($checkpoint, new \App\Formatters\ModelFormatter);
    }
}
