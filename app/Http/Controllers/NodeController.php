<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Node;
use App\Formatters\NodeFormatter;

class NodeController extends SingularController
{
    protected $endpoint = 'node';

    public function __construct(Node $repository, NodeFormatter $formatter)
    {
        parent::__construct($repository, $formatter);
    }
}
