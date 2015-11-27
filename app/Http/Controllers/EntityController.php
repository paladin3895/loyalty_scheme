<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EntityController extends ApiController
{
    protected $endpoint = 'entity';

    protected $repository = 'App\Models\Entity';

    public function create(Request $request)
    {
        if (!$request->has($this->endpoint))
            throw new \Exception('no endpoint data');
        $data = $request->input($this->endpoint);
        $attributes = [];
        $entity = $this->repository->newInstance();
        foreach ($data as $key => $value) {
            $entity->$key = $value;
        }
        if (!$entity->save()) throw new \Exception('cannot create endpoint');
        return $this->success($entity->toArray());
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

    public function change($id, Request $request)
    {

    }
}
