<?php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Formatters\ModelFormatter;

class EventCompoundController extends CompoundController
{
    protected $repository;

    protected $endpoints = [
        'subscriber' => [
            'methods' => ['index', 'show', 'create', 'update', 'replace', 'delete'],
            'controller' => 'App\Http\Controllers\SubscriberController',
            'formatter' => 'App\Formatters\SubscriberFormatter',
        ]
    ];

    public function __construct(Event $repository)
    {
        parent::__construct($repository);
    }
}
