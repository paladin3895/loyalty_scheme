<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
            $response->header("Data-{$this->endpoint}-{$key}", $value);
        }
        return $response;
    }

    public function show($id)
    {
        $record = $this->repository->find($id);
        if (!$record) throw new \Exception('endpoint not found');
        return $this->response->item($record, $this->formatter);
    }

    public function delete($id)
    {
        $record = $this->repository->find($id);
        if (!$record) throw new \Exception('endpoint not found');
        if (!$record->delete())
            throw new \Exception('cannot delete endpoint');
        return $this->response->item($record, $this->formatter);
    }

    public function create(Request $request)
    {
        if (!$request->has($this->endpoint))
            throw new \Exception('no endpoint data');
        $data = $request->input($this->endpoint);
        $record = $this->repository->newInstance();
        foreach ($data as $key => $value) {
            $record->$key = $value;
        }
        if (!$record->save()) throw new \Exception('cannot create endpoint');
        return $this->response->item($record, $this->formatter);
    }

    public function replace($id, Request $request)
    {
        if (!$request->has($this->endpoint))
            throw new \Exception('no endpoint data');
        $data = $request->input($this->endpoint);
        $record = $this->repository->find($id);
        if (!$record) throw new \Exception('endpoint not found');
        $record->clearDynamicFields();
        foreach ($data as $key => $value) {
            $record->$key = $value;
        }
        if (!$record->update($data))
            throw new \Exception('cannot update endpoint');
        return $this->response->item($record, $this->formatter);
    }

    public function update($id, Request $request)
    {
        if (!$request->has($this->endpoint))
            throw new \Exception('no endpoint data');
        $data = $request->input($this->endpoint);
        $record = $this->repository->find($id);
        if (!$record) throw new \Exception('endpoint not found');
        foreach ($data as $key => $value) {
            if (preg_match('#^([\+\-])([0-9]+)$#', $value, $matches)) {
                switch($matches[1]) {
                    case '+': $record->$key += $matches[2]; break;
                    case '-': $record->$key -= $matches[2]; break;
                }
            } else {
                $record->$key = $value;
            }
        }
        if (!$record->update($data))
            throw new \Exception('cannot update endpoint');
        return $this->response->item($record, $this->formatter);
    }
}
