<?php
namespace App\Formatters;

use League\Fractal\TransformerAbstract;
use App\Models\Schema;

class SchemaFormatter extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'nodes', 'links'
    ];

    public function transform(Schema $schema)
    {
        return $schema->toArray();
    }

    protected function includeNodes(Schema $schema)
    {
        return $this->collection($schema->node()->get(), new NodeFormatter);
    }

    protected function includeLinks(Schema $schema)
    {
        return $this->collection($schema->link()->get(), new LinkFormatter);
    }
}