<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Link;
use App\Formatters\LinkFormatter;

class LinkController extends SingularController
{
    protected $endpoint = 'link';

    public function __construct(Link $repository, LinkFormatter $formatter)
    {
        parent::__construct($repository, $formatter);
    }
}
