<?php
namespace App\Formatters;

use App\Models\Schema;

class SchemaFormatter extends ModelFormatter
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'nodes', 'links'
    ];

    protected function includeNodes(Schema $schema)
    {
        return $this->collection($schema->nodes()->get(), new NodeFormatter);
    }

    protected function includeLinks(Schema $schema)
    {
        return $this->collection($schema->links()->get(), new LinkFormatter);
    }
}
