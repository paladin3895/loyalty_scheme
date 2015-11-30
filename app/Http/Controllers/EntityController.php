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
        $record = $this->repository->newInstance();
        foreach ($data as $key => $value) {
            $record->$key = $value;
        }
        if (!$record->save()) throw new \Exception('cannot create endpoint');
        return $this->success($record->toArray());
    }

    public function edit($id, Request $request)
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
        return $this->success($record->toArray());
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
        return $this->success($record->toArray());
    }
}
