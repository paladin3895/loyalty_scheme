<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Link;
use App\Formatters\LinkFormatter;

class LinkController extends SingularController
{
    protected $endpoint = 'link';

    public function __construct(Link $repository, LinkFormatter $formatter, Relation $relation = null)
    {
        parent::__construct($repository, $formatter, $relation);
    }
}
