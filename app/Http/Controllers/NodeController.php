<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Node;
use App\Formatters\NodeFormatter;

class NodeController extends SingularController
{
    protected $endpoint = 'node';

    public function __construct(Node $repository, NodeFormatter $formatter, Relation $relation = null)
    {
        parent::__construct($repository, $formatter, $relation);
    }
}
