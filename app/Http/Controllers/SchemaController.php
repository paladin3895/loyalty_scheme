<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Helper;
use Liquid\Records\Record;

class SchemaController extends SingularController
{
    protected $endpoint = 'schema';

    protected $repository = 'App\Models\Schema';

    protected $relations = ['node', 'link'];

    public function apply($id, Request $request)
    {
        $schema = $this->repository->find($id);
        if (!$schema) throw new \Exception('endpoint not found');

        $registry = Helper::buildSchema($schema);
        $record = new Record(
            $request->input('data') ? : [],
            $request->input('checkpoint') ? : []
        );
        $registry->process($record);
        return $this->success(Record::$history);
    }
}
