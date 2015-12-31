<?php
namespace App\Formatters;

use League\Fractal\TransformerAbstract;
use App\Models\BaseModel;

class ModelFormatter extends TransformerAbstract
{
    public function transform(BaseModel $model)
    {
        return $model->toArray();
    }
}
