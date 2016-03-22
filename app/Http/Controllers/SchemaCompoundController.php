<?php
namespace App\Http\Controllers;

use App\Models\Schema;
use App\Models\Entity;
use App\Http\Helpers;
use Liquid\Records\Record;
use App\Exceptions\ExceptionResolver;
use App\Formatters\ModelFormatter;

class SchemaCompoundController extends CompoundController
{
    protected $repository;

    protected $endpoints = [
        'node' => [
            'methods' => [
                'index', 'show', 'create', 'replace', 'update', 'delete'
            ],
            'controller' => 'App\Http\Controllers\NodeController',
            'formatter' => 'App\Formatters\NodeFormatter',
        ],
        'link' => [
            'methods' => [
                'index', 'show', 'create', 'replace', 'update', 'delete'
            ],
            'controller' => 'App\Http\Controllers\LinkController',
            'formatter' => 'App\Formatters\LinkFormatter',
        ],
    ];

    public function __construct(Schema $repository, ModelFormatter $formatter)
    {
        parent::__construct($repository, $formatter);
    }
}
