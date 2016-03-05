<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Exceptions\ExceptionResolver;

use App\Models\BaseModel;
use App\Models\Interfaces\BelongsToClient;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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

        // filtering section
        $filter = $request->input('filter');
        if (is_array($filter)) {
            foreach ($filter as $field => $value) {
                $operation = '=';
                if (preg_match('#(\<\=|\>\=|\<\>|\<|\>)(.+)#', $value, $matches)) {
                    list(, $operation, $value) = $matches;
                }
                $this->repository = $this->repository->where($field, $operation, $value);
            }
        }

        // sorting section
        $sort = $request->input('sort');
        if (is_array($sort)) {
            foreach ($sort as $field => $order) {
                if (!in_array($order, ['asc', 'desc'])) continue;
                $this->repository = $this->repository->orderBy($field, $order);
            }
        }

        // pagination section
        $perPage = $request->input('per_page') ? : 10;
        $records = $this->repository->paginate($perPage);

        foreach ($request->all() as $key => $value) {
            $records->appends($key, $value);
        }

        return $this->response->paginator(
            $records,
            $this->formatter
        );
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
        if (!$request->has('data'))
            throw ExceptionResolver::resolve('bad request', "please provide data for {$this->endpoint}");
        $data = $request->input('data');

        if ($this->repository instanceof BaseModel) {
            $record = $this->repository->newInstance();
        } elseif ($this->repository instanceof HasMany) {
            $record = $this->repository->getRelated();
            $record->{$this->repository->getPlainForeignKey()} = $this->relation->getParentKey();
        } else {
            throw ExceptionResolver::resolve('conflict', "cannot create {$this->endpoint} via this relation");
        }

        foreach ($data as $key => $value) {
            $record->$key = $value;
        }

        if ($record instanceof BelongsToClient) {
            $record->setClientIdAttribute($this->auth->user());
        }

        if (!$record->save())
            throw ExceptionResolver::resolve('resource', "cannot create new {$this->endpoint}");
        return $this->response->item($record, $this->formatter);
    }

    public function replace($id, Request $request)
    {
        if ($this->relation) $this->associate();
        if (!$request->has('data'))
            throw ExceptionResolver::resolve('bad request', "please provide data for {$this->endpoint}");
        $data = $request->input('data');
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
        if (!$request->has('data'))
            throw ExceptionResolver::resolve('bad request', "please provide data for {$this->endpoint}");
        $data = $request->input('data');
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
        if ($this->relation instanceof HasMany) {
            $this->repository = $this->relation;
        } elseif ($this->relation instanceof HasManyThrough) {
            $this->repository = $this->relation;
        }
    }
}
