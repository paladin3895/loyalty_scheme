<?php

namespace App\Http\Controllers;

use Liquid\Schema;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Subscriber;
use App\Formatters\SubscriberFormatter;

class SubscriberController extends SingularController
{
    protected $endpoint = 'subscriber';

    public function __construct(Subscriber $repository, SubscriberFormatter $formatter)
    {
        parent::__construct($repository, $formatter);
    }
}
