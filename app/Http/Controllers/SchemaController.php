<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Liquid\Schema as SchemaManager;

class SchemaController extends SingularController
{
    protected $endpoint = 'schema';

    protected $repository = 'App\Models\Schema';

    public function apply($id, Request $request, SchemaManager $manager)
    {
        $schema = $this->repository->find($id);
        if (!$schema) throw new \Exception('endpoint not found');

        $registry = $manager->build($schema->toArray());
        $result = $registry->process($request);
        return $this->success($result);
    }
}
