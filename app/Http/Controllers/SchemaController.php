<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SchemaController extends ApiController
{
    protected $endpoint = 'schema';

    protected $repository = 'App\Models\Schema';
}
