<?php

namespace App\Http\Controllers;

use Liquid\Schema;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Event;
use App\Formatters\EventFormatter;

class EventController extends SingularController
{
    protected $endpoint = 'event';

    public function __construct(Event $repository, EventFormatter $formatter)
    {
        parent::__construct($repository, $formatter);
    }

    public function apply($id, Request $request)
    {
      
    }
}
