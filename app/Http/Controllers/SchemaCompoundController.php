<?php
namespace App\Http\Controllers;

use App\Models\Schema;

class SchemaCompoundController extends CompoundController
{
    protected $repository;

    protected $endpoints = [
        'node' => ['list', 'show', 'create', 'update', 'delete'],
        'link' => ['list', 'show', 'create', 'update', 'delete'],
    ];

    public function __construct(Schema $schema)
    {
        $this->repository = $schema;
    }
}
