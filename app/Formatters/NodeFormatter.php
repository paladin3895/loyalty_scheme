<?php
namespace App\Formatters;

use App\Models\Node;
use App\Models\BaseModel;

class NodeFormatter extends ModelFormatter
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [];

    public function transform(BaseModel $model)
    {
        $response = [];
        $model = $model->toArray();
        $response['id'] = $model['id'];
        $response['schema_id'] = $model['schema_id'];
        foreach ((array)$model['attributes'] as $key => $value) {
          $response[$key] = $value;
        }
        $response['config'] = isset($model['config']) ? $model['config'] : null;
        $response['policies'] = isset($model['policies']) ? $model['policies'] : null;
        $response['rewards'] = isset($model['rewards']) ? $model['rewards'] : null;
        return $response;
    }
}
