<?php

namespace App\Http\Controllers;

use Liquid\Schema;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EntityController extends SingularController
{
    protected $endpoint = 'entity';

    protected $repository = 'App\Models\Entity';
}
