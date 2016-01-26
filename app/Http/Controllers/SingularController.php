<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Exceptions\ExceptionResolver;

abstract class SingularController extends BaseApiController
{
    protected $endpoint;

    public function __construct($repository, $formatter) {
        $this->repository = $repository;
        $this->formatter = $formatter;
    }

    public function index(Request $request)
    {
        $records = $this->repository->get();
        return $this->response->collection($records, $this->formatter);
    }

    public function extract(Request $request)
    {
        $results['count'] = $this->repository->count();
        $response = new Response();
        foreach ($results as $key => $value) {
            $response->header("Data-{$key}", $value);
        }
        return $response;
    }

    public function show($id)
    {
        $record = $this->repository->find($id);
        if (!$record)
            throw ExceptionResolver::resolve('not found', "{$this->endpoint} not found");
        return $this->response->item($record, $this->formatter);
    }

    public function delete($id)
    {
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
        if (!$record->save())
            throw ExceptionResolver::resolve('resource', "cannot create new {$this->endpoint}");
        return $this->response->item($record, $this->formatter);
    }

    public function replace($id, Request $request)
    {
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
        if (!$request->has($this->endpoint))
            throw ExceptionResolver::resolve('bad request', "please provide data for {$this->endpoint}");
        $data = $request->input($this->endpoint);
        $record = $this->repository->find($id);
        if (!$record)
            throw ExceptionResolver::resolve('not found', "{$this->endpoint} with id {$id} not exists");
        foreach ($data as $key => $value) {
            if (preg_match('#^([\+\-])([0-9]+)$#', $value, $matches)) {
                switch($matches[1]) {
                    case '+': $record->$key += $matches[2]; break;
                    case '-': $record->$key -= $matches[2]; break;
                }
            } elseif ($value === '') {
                $record->$key = null;
            } else {
                $record->$key = $value;
            }
        }
        if (!$record->save())
            throw ExceptionResolver::resolve('resource', "cannot update {$this->endpoint} with id {$id}");
        return $this->response->item($record, $this->formatter);
    }
}
