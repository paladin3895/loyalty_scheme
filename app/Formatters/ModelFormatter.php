<?php
namespace App\Formatters;

use League\Fractal\TransformerAbstract;
use App\Models\BaseModel;
use App\Models\Interfaces\BelongsToClient;

class ModelFormatter extends TransformerAbstract
{
    public function transform(BaseModel $model)
    {
        $buffer = $model->toArray();
        if ($model instanceof BelongsToClient) {
            $buffer['external_id'] = $model->external_id ? : null;
        }
        return $buffer;
    }
}
