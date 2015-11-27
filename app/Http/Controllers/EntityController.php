<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EntityController extends ApiController
{
    protected $endpoint = 'entity';

    protected $validations = [];

    protected $repository = 'App\Models\Entity';
}
