<?php
namespace App\Http\Controllers;

use App\Models\Entity;

class EntityCompoundController extends CompoundController
{
    protected $repository;

    protected $endpoints = [
        'privilege' => ['list', 'show', 'create', 'update', 'replace', 'delete'],
        'checkpoint' => ['list', 'show', 'delete']
    ];

    public function __construct(Entity $entity)
    {
        $this->repository = $entity;
    }
}
