<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SchemaController extends SingularController
{
    protected $endpoint = 'schema';

    protected $repository = 'App\Models\Schema';
}
