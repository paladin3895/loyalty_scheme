<?php
namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use ReflectionClass;

abstract class CompoundController extends BaseController
{
    protected $repository;

    protected $foreignKey;

    protected $validations = [];

    protected $endpoints = [];

    protected $allowMethods = [];

    public function listEndpoint($id, $endpoint)
    {
        $records = $this->resolveEndpoint($endpoint)->where($foreignKey, $id)->get();
        return $this->success([$endpoint => $records]);
    }

    public function showEndpoint($id, $endpoint, $endpoint_id)
    {
        $record = $this->resolveEndpoint($endpoint)->where($foreignKey, $id)->where('id', $endpoint_id)->first();
        if (!$record)
            throw new \Exception('endpoint not exists');
        return $this->success([$endpoint => $record];
    }

    public function createEndpoint($id, $endpoint, Request $request)
    {
        $data = $request->input($endpoint);
        if (!$data)
            throw new \Exception('missing data for endpoint');
        $data = $this->validate($data);
        $data[$this->foreignKey] = $id;
        $record = $this->resolveEndpoint($endpoint)->create($data);
        if (!$record)
            throw new \Exception('cannot create endpoint');
        return $this->success([$endpoint => $record]);
    }

    public function updateEndpoint($id, $endpoint, $endpoint_id, Request $request)
    {
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
        $record = $this->resolveEndpoint($endpoint)->where('id', $endpoint_id)->first();
        if (!$record)
            throw new \Exception('endpoint not exists');
        if (!$record->delete())
            throw new \Exception('cannot delete endpoint');
        return $this->success([$endpoint => $record]);
    }

    protected function resolveEndpoint($endpoint)
    {
        if (!array_key_exists($endpoint, $this->endpoints))
            throw new \Exception('invalid endpoint');
        $class = new ReflectionClass($this->endpoints[$endpoint]);
        if (!$class->isInstantiable())
            throw new \Exception('cannot create object with endpoint');
        return $class->newInstance();
    }

    protected function validate(array $data)
    {
        return true;
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
