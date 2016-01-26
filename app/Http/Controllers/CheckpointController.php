<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Checkpoint;
use App\Formatters\ModelFormatter;

class CheckpointController extends SingularController
{
    protected $endpoint = 'checkpoint';

    public function __construct(Checkpoint $repository, ModelFormatter $formatter)
    {
        parent::__construct($repository, $formatter);
    }
}
