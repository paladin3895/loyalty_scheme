<?php
namespace App\Http\Controllers;

use App\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Exceptions\ExceptionResolver;
use App\Http\Helpers;

abstract class CompoundController extends BaseApiController
{
    protected $repository;

    protected $formatter;

    protected $endpoints = [];

    public function __construct($repository, $formatter)
    {
        $this->repository = $repository;
        $this->formatter = $formatter;
    }

    public function listEndpoint($id, $endpoint)
    {
        $this->checkEndpoint($endpoint, __FUNCTION__);
        $records = $this->resolveEndpoint($id, $endpoint)->get();
        return $this->collection($records, $this->formatter);
    }

    public function showEndpoint($id, $endpoint, $endpoint_id)
    {
        $this->checkEndpoint($endpoint, __FUNCTION__);
        $record = $this->resolveEndpoint($id, $endpoint)->where('id', $endpoint_id)->first();
        if (!$record)
            throw ExceptionResolver::resolve('not found', "{$endpoint} not found");
        return $this->item($record, $this->formatter);
    }

    public function createEndpoint($id, $endpoint, Request $request)
    {
        $this->checkEndpoint($endpoint, __FUNCTION__);
        $data = $request->input(Helpers::singular($endpoint));
        if (!$data)
            throw ExceptionResolver::resolve('bad request', "please provide data for " . Helpers::singular($endpoint));
        $record = $this->resolveEndpoint($id, $endpoint)->create($data);
        if (!$record)
            throw ExceptionResolver::resolve('resource', "cannot create new {$endpoint}");
        return $this->item($record, $this->formatter);
    }

    public function updateEndpoint($id, $endpoint, $endpoint_id, Request $request)
    {
        $this->checkEndpoint($endpoint, __FUNCTION__);
        $data = $request->input(Helpers::singular($endpoint));
        if (!$data)
            throw ExceptionResolver::resolve('bad request', "please provide data for " . Helpers::singular($endpoint));
        $record = $this->resolveEndpoint($id, $endpoint)->where('id', $endpoint_id)->first();
        if (!$record)
            throw ExceptionResolver::resolve('not found', "{$endpoint} with id {$endpoint_id} not exists");
        $data = array_replace_recursive($record->toArray(), $data);
        foreach ($data as $key => $value) {
            $record->$key = $value;
        }
        if (!$record->update($data))
            throw ExceptionResolver::resolve('resource', "cannot update {$endpoint} with id {$endpoint_id}");
        return $this->item($record, $this->formatter);
    }

    public function replaceEndpoint($id, $endpoint, $endpoint_id, Request $request)
    {
        $this->checkEndpoint($endpoint, __FUNCTION__);
        $data = $request->input($endpoint);
        if (!$data)
            throw ExceptionResolver::resolve('bad request', "please provide data for " . Helpers::singular($endpoint));
        $record = $this->resolveEndpoint($id, $endpoint)->where('id', $endpoint_id)->first();
        if (!$record)
            throw ExceptionResolver::resolve('not found', "{$endpoint} with id {$endpoint_id} not exists");
        $record->clearDynamicFields();
        foreach ($data as $key => $value) {
            $record->$key = $value;
        }
        if (!$record->update($data))
            throw ExceptionResolver::resolve('resource', "cannot replace {$endpoint} with id {$endpoint_id}");
        return $this->item($record, $this->formatter);
    }

    public function deleteEndpoint($id, $endpoint, $endpoint_id)
    {
        $this->checkEndpoint($endpoint, __FUNCTION__);
        $record = $this->resolveEndpoint($id, $endpoint)->where('id', $endpoint_id)->first();
        if (!$record)
            throw ExceptionResolver::resolve('not found', "{$endpoint} with id {$endpoint_id} not exists");
        if (!$record->delete())
            throw ExceptionResolver::resolve('resource', "cannot delete {$endpoint} with id {$endpoint_id}");
        return $this->item($record, $this->formatter);
    }

    protected function resolveEndpoint($id, $endpoint)
    {
        $record = $this->repository->where('id', $id)->first();
        if (!$record)
            throw ExceptionResolver::resolve('not found', "parent endpoint not found");
        if (!is_callable([$record, Helpers::plural($endpoint)]))
            throw ExceptionResolver::resolve('not found', "cannot access nested endpoint {$endpoint}");
        return call_user_func([$record, Helpers::plural($endpoint)]);
    }

    protected function checkEndpoint($endpoint, $method)
    {
        $endpoint = Helpers::singular($endpoint);
        if (!array_key_exists($endpoint, $this->endpoints))
            throw ExceptionResolver::resolve('not found', "endpoint {$endpoint} not found");
        if (!preg_match('#^([a-z]+)Endpoint$#', $method, $matches))
            throw ExceptionResolver::resolve('method not allowed', "invalid method");
        $method = $matches[1];
        if (!in_array($method, $this->endpoints[$endpoint]))
            throw ExceptionResolver::resolve('method not allowed', "method {$method} is not allowed in this endpoint");
    }
}
