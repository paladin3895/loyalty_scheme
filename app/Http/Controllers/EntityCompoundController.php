<?php
namespace App\Http\Controllers;

use App\Models\Entity;
use App\Formatters\ModelFormatter;

class EntityCompoundController extends CompoundController
{
    protected $repository;

    protected $endpoints = [
        'checkpoint' => ['list', 'show', 'delete']
    ];

    public function __construct(Entity $repository, ModelFormatter $formatter)
    {
        parent::__construct($repository, $formatter);
    }
}
