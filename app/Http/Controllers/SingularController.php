<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Exceptions\ExceptionResolver;
use Illuminate\Database\Eloquent\Relations\Relation;

abstract class SingularController extends BaseApiController
{
    protected $endpoint;

    protected $relation;

    public function __construct($repository, $formatter, Relation $relation = null) {
        $this->repository = $repository;
        $this->formatter = $formatter;
        $this->relation = $relation;
    }

    public function index(Request $request)
    {
        if ($this->relation) $this->associate();
        $records = $this->repository->get();
        return $this->response->collection($records, $this->formatter);
    }

    public function extract(Request $request)
    {
        if ($this->relation) $this->associate();
        $results['count'] = $this->repository->count();
        $response = new Response();
        foreach ($results as $key => $value) {
            $response->header("Data-{$key}", $value);
        }
        return $response;
    }

    public function show($id)
    {
        if ($this->relation) $this->associate();
        $record = $this->repository->find($id);
        if (!$record)
            throw ExceptionResolver::resolve('not found', "{$this->endpoint} not found");
        return $this->response->item($record, $this->formatter);
    }

    public function delete($id)
    {
        if ($this->relation) $this->associate();
        $record = $this->repository->find($id);
        if (!$record)
            throw ExceptionResolver::resolve('not found', "{$this->endpoint} with id {$id} not exists");
        if (!$record->delete())
            throw ExceptionResolver::resolve('resource', "cannot delete {$this->endpoint} with id {$id}");
        return $this->response->item($record, $this->formatter);
    }

    public function create(Request $request)
    {
        if (!$request->has($this->endpoint))
            throw ExceptionResolver::resolve('bad request', "please provide data for {$this->endpoint}");
        $data = $request->input($this->endpoint);
        $record = $this->repository->newInstance();
        foreach ($data as $key => $value) {
            $record->$key = $value;
        }
        if ($this->relation)
          $record->{$this->relation->getPlainForeignKey()} = $this->relation->getParentKey();
        if (!$record->save())
            throw ExceptionResolver::resolve('resource', "cannot create new {$this->endpoint}");
        return $this->response->item($record, $this->formatter);
    }

    public function replace($id, Request $request)
    {
        if ($this->relation) $this->associate();
        if (!$request->has($this->endpoint))
            throw ExceptionResolver::resolve('bad request', "please provide data for {$this->endpoint}");
        $data = $request->input($this->endpoint);
        $record = $this->repository->find($id);
        if (!$record)
            throw ExceptionResolver::resolve('not found', "{$this->endpoint} with id {$id} not exists");
        $record->clear();
        foreach ($data as $key => $value) {
            $record->$key = $value;
        }
        if (!$record->save())
            throw ExceptionResolver::resolve('resource', "cannot replace {$this->endpoint} with id {$id}");
        return $this->response->item($record, $this->formatter);
    }

    public function update($id, Request $request)
    {
        if ($this->relation) $this->associate();
        if (!$request->has($this->endpoint))
            throw ExceptionResolver::resolve('bad request', "please provide data for {$this->endpoint}");
        $data = $request->input($this->endpoint);
        $record = $this->repository->find($id);
        if (!$record)
            throw ExceptionResolver::resolve('not found', "{$this->endpoint} with id {$id} not exists");
        foreach ($data as $key => $value) {
            $record->$key = $value;
        }
        if (!$record->save())
            throw ExceptionResolver::resolve('resource', "cannot update {$this->endpoint} with id {$id}");
        return $this->response->item($record, $this->formatter);
    }

    protected function associate()
    {
        $this->repository = $this->repository->where($this->relation->getForeignKey(), $this->relation->getParentKey());
    }
}
