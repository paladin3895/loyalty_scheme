<?php
namespace App\Formatters;

use App\Models\Schema;
use App\Models\BaseModel;

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

    public function transform(BaseModel $model)
    {
        $response = [];
        $model = $model->toArray();
        $response['id'] = $model['id'];
        foreach ($model['attributes'] as $key => $value) {
          $response[$key] = $value;
        }
        return $response;
    }

    protected function includeNodes(Schema $schema)
    {
        return $this->collection($schema->nodes()->get(), new NodeFormatter);
    }

    protected function includeLinks(Schema $schema)
    {
        return $this->collection($schema->links()->get(), new LinkFormatter);
    }
}
