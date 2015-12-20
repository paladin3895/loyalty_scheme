<?php
namespace App\Http\Controllers;

use App\Models\Schema;
use Liquid\Schema as SchemaManager;

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

    public function applyEndpoint($id, $endpoint, $endpoint_id, SchemaManager $manager)
    {
        $this->checkEndpoint($endpoint, __FUNCTION__);
        $schema = $this->repository->where('id', $id)->first();
        if (!$schema)
            throw new \Exception('schema not exists');

        $record = $this->resolveEndpoint($id, $endpoint)->where('id', $endpoint_id)->first();
        if (!$record)
            throw new \Exception('endpoint not exists');

        $schema = $manager->build($schema->toArray());
        $result = $schema->process($record->toArray());

        return $this->success([$endpoint => $result]);
    }
}
