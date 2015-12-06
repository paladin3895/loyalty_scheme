<?php
namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

abstract class CompoundController extends BaseController
{
    protected $repository;

    protected $endpoints = [];

    public function listEndpoint($id, $endpoint)
    {
        $this->checkEndpoint($endpoint, __FUNCTION__);
        $records = $this->resolveEndpoint($endpoint)->get();
        return $this->success([$endpoint => $records]);
    }

    public function showEndpoint($id, $endpoint, $endpoint_id)
    {
        $this->checkEndpoint($endpoint, __FUNCTION__);
        $record = $this->resolveEndpoint($endpoint)->where('id', $endpoint_id)->first();
        if (!$record)
            throw new \Exception('endpoint not exists');
        return $this->success([$endpoint => $record]);
    }

    public function createEndpoint($id, $endpoint, Request $request)
    {
        $this->checkEndpoint($endpoint, __FUNCTION__);
        $data = $request->input($endpoint);
        if (!$data)
            throw new \Exception('missing data for endpoint');
        $record = $this->resolveEndpoint($endpoint)->create($data);
        if (!$record)
            throw new \Exception('cannot create endpoint');
        return $this->success([$endpoint => $record]);
    }

    public function updateEndpoint($id, $endpoint, $endpoint_id, Request $request)
    {
        $this->checkEndpoint($endpoint, __FUNCTION__);
        $data = $request->input($endpoint);
        if (!$data)
            throw new \Exception('missing data for endpoint');
        $record = $this->resolveEndpoint($endpoint)->where('id', $endpoint_id)->first();
        if (!$record)
            throw new \Exception('endpoint not exists');
        if (!$record->update($data))
            throw new \Exception('cannot update endpoint');
        return $this->success([$endpoint => $record]);
    }

    public function deleteEndpoint($id, $endpoint, $endpoint_id)
    {
        $this->checkEndpoint($endpoint, __FUNCTION__);
        $record = $this->resolveEndpoint($endpoint)->where('id', $endpoint_id)->first();
        if (!$record)
            throw new \Exception('endpoint not exists');
        if (!$record->delete())
            throw new \Exception('cannot delete endpoint');
        return $this->success([$endpoint => $record]);
    }

    protected function resolveEndpoint($endpoint)
    {
        if (!is_callable([$this->repository, "{$endpoint}s"]))
            throw new \Exception('endpoint is not valid');
        return call_user_func([$this->repository, "{$endpoint}s"]);
    }

    protected function checkEndpoint($endpoint, $method)
    {
        if (!array_key_exists($endpoint, $this->endpoints))
            throw new \Exception('invalid endpoint');
        if (!preg_match('#^([a-z]+)Endpoint$#', $method, $matches))
            throw new \Exception('invalid method');
        $method = $matches[1];
        if (!in_array($method, $this->endpoints[$endpoint]))
            throw new \Exception('the method in this endpoint is not allowed');
    }

    protected function success(array $data)
    {
        $results = [];
        $results['status'] = 1;
        foreach ($data as $key => $value) {
            $results[$key] = $value;
        }
        return $results;
    }
}
