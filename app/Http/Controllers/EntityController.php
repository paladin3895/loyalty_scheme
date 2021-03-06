<?php

namespace App\Http\Controllers;

use Liquid\Schema;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Entity;
use App\Formatters\EntityFormatter;

class EntityController extends SingularController
{
    protected $endpoint = 'entity';

    public function __construct(Entity $repository, EntityFormatter $formatter)
    {
        parent::__construct($repository, $formatter);
    }
}
