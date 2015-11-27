<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Entity;

abstract class ApiController extends BaseController
{
    protected $endpoint;

    protected $validations;

    protected $repository;

    public function __construct() {
        $this->repository = new $this->repository;
    }

    public function index(Request $request)
    {
        $records = $this->repository->get();
        return $this->success($records->toArray());
    }

    public function extract(Request $request)
    {
        $records = $this->repository->count();
        return $this->success(['count' => $records]);
    }

    public function show($id)
    {
        $record = $this->repository->find($id);
        return $this->success($record->toArray());
    }

    public function create(Request $request)
    {
        if (!$request->has($this->endpoint))
            throw new \Exception('no endpoint data');
        $data = $request->input($this->entpoind);
        $record = $this->repository->create($data);
        if (!$record) throw new \Exception('cannot create endpoint');
        return $this->success($record->toArray());
    }

    public function update($id, Request $request)
    {
        if (!$request->has($this->endpoint))
            throw new \Exception('no endpoint data');
        $data = $request->input($this->entpoind);
        $record = $this->repository->find($id);
        if (!$record) throw new \Exception('endpoint not found');
        if (!$record->update($data))
            throw new \Exception('cannot update endpoint');
        return $this->success($record->toArray());
    }

    public function delete($id)
    {
        $record = $this->repository->find($id);
        if (!$record) throw new \Exception('endpoint not found');
        if (!$record->delete())
            throw new \Exception('cannot delete endpoint');
        return $this->success($record->toArray());
    }

    protected function success(array $data)
    {
        return [
            'status' => 1,
            "$this->endpoint" => $data
        ];
    }
}
