<?php
namespace App\Formatters;

use App\Models\Node;

class NodeFormatter extends ModelFormatter
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [];
    
}
