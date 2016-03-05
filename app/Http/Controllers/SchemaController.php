<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Helpers;
use Liquid\Records\Record;
use App\Models\Schema;
use App\Formatters\SchemaFormatter;
use App\Exceptions\ExceptionResolver;

class SchemaController extends SingularController
{
    protected $endpoint = 'schema';

    public function __construct(Schema $repository, SchemaFormatter $formatter)
    {
        parent::__construct($repository, $formatter);

        $this->middleware('oauth');

        $this->scopes('read.schema', ['only' => ['index', 'extract', 'show']]);
        $this->scopes('edit.schema', ['only' => ['create', 'update', 'delete']]);
        $this->scopes('execute.schema', ['only' => ['apply']]);
    }

    public function apply($id, Request $request)
    {
        $schema = $this->repository->find($id);
        if (!$schema)
            throw ExceptionResolver::resolve('not found', "schema with id {$id} not exists");
        $registry = Helpers::buildSchema($schema);

        $record = new Record(
            $request->input('data') ? : [],
            $request->input('checkpoints') ? : []
        );
        $registry->process($record);
        return $this->response->array([
            'status' => 1,
            'data' => json_decode(json_encode(Record::$history, JSON_FORCE_OBJECT)),
        ]);
    }
}
