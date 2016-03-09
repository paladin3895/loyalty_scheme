<?php
namespace App\Formatters;

use App\Models\BaseModel;
use App\Models\Subscriber;

class SubscriberFormatter extends ModelFormatter
{
    public function transform(BaseModel $model)
    {
        if (!$model instanceof Subscriber) return $model->toArray();
        $subscriber = [];
        foreach ($model->toArray() as $field => $value) {
            if ($field == 'schema_id') {
                $subscriber['schema'] = $model->schema->get()->toArray();
            } else {
                $subscriber[$field] = $value;
            }
        }
        return $subscriber;
    }
}
