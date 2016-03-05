<?php
namespace App\Http\Controllers;

use App\Models\Entity;
use App\Formatters\ModelFormatter;

class EntityCompoundController extends CompoundController
{
    protected $repository;

    protected $endpoints = [
        'checkpoint' => [
            'methods' => ['index', 'show', 'delete'],
            'controller' => 'App\Http\Controllers\CheckpointController',
            'formatter' => 'App\Formatters\CheckpointFormatter',
        ]
    ];

    public function __construct(Entity $repository)
    {
        parent::__construct($repository);
    }
}
