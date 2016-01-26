<?php
namespace App\Http\Controllers;

use App\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Exceptions\ExceptionResolver;
use App\Http\Helpers;

use League\Fractal\TransformerAbstract as Transformer;
use App\Models\BaseModel;

abstract class CompoundController extends BaseApiController
{
    protected $repository;

    protected $endpoints;

    public function __construct(BaseModel $repository)
    {
        $this->repository = $repository;
    }

    public function indexEndpoint($id, $endpoint, Request $request)
    {
        $this->checkEndpoint($endpoint, __FUNCTION__);
        return $this->resolveController($id, Helpers::singular($endpoint))
                    ->index($request);
    }

    public function showEndpoint($id, $endpoint, $endpoint_id)
    {
        $this->checkEndpoint($endpoint, __FUNCTION__);
        return $this->resolveController($id, $endpoint)
                    ->show($endpoint_id);
    }

    public function createEndpoint($id, $endpoint, Request $request)
    {
        $this->checkEndpoint($endpoint, __FUNCTION__);
        return $this->resolveController($id, Helpers::singular($endpoint))
                    ->create($request);
    }

    public function updateEndpoint($id, $endpoint, $endpoint_id, Request $request)
    {
        $this->checkEndpoint($endpoint, __FUNCTION__);
        return $this->resolveController($id, $endpoint)
                    ->update($endpoint_id, $request);
    }

    public function replaceEndpoint($id, $endpoint, $endpoint_id, Request $request)
    {
        $this->checkEndpoint($endpoint, __FUNCTION__);
        return $this->resolveController($id, $endpoint)
                    ->replace($endpoint_id, $request);
    }

    public function deleteEndpoint($id, $endpoint, $endpoint_id)
    {
        $this->checkEndpoint($endpoint, __FUNCTION__);
        return $this->resolveController($id, $endpoint)
                    ->delete($endpoint_id);
    }

    protected function resolveRepository($id, $endpoint)
    {
        $record = $this->repository->where('id', $id)->first();
        if (!$record)
            throw ExceptionResolver::resolve('not found', "parent endpoint not found");
        if (!is_callable([$record, Helpers::plural($endpoint)]))
            throw ExceptionResolver::resolve('not found', "cannot access nested endpoint {$endpoint}");
        return call_user_func([$record, Helpers::plural($endpoint)])->getRelated();
    }

    protected function resolveFormatter($endpoint)
    {
        $ref = new \ReflectionClass($this->endpoints[$endpoint]['formatter']);
        if ($ref->isInstantiable() && $ref->isSubclassOf('App\Formatters\ModelFormatter')) {
            return $ref->newInstance();
        } else {
            throw ExceptionResolver::resolve('error', "misconfig format for nested endpoint {$endpoint}");
        }
    }

    protected function resolveController($id, $endpoint)
    {
        if (!array_key_exists($endpoint, $this->endpoints))
            throw ExceptionResolver::resolve('not found', "endpoint {$endpoint} not found");
        $ref = new \ReflectionClass($this->endpoints[$endpoint]['controller']);
        if ($ref->isInstantiable() && $ref->isSubclassOf('App\Http\Controllers\SingularController')) {
            return $ref->newInstance(
                $this->resolveRepository($id,$endpoint),
                $this->resolveFormatter($endpoint)
            );
        } else {
            throw ExceptionResolver::resolve('error', "misconfig for nested endpoint {$endpoint}");
        }
    }

    protected function checkEndpoint($endpoint, $method)
    {
        $endpoint = Helpers::singular($endpoint);
        if (!array_key_exists($endpoint, $this->endpoints))
            throw ExceptionResolver::resolve('not found', "endpoint {$endpoint} not found");
        if (!preg_match('#^([a-z]+)Endpoint$#', $method, $matches))
            throw ExceptionResolver::resolve('method not allowed', "invalid method");
        $method = $matches[1];
        if (!in_array($method, $this->endpoints[$endpoint]['methods']))
            throw ExceptionResolver::resolve('method not allowed', "method {$method} is not allowed in this endpoint");
    }
}
