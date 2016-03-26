<?php
namespace App\Models\Observers;

use App\Models\Event;

use App\Exceptions\ExceptionResolver;

class EventObserver
{
    public function saving(Event $model)
    {
        if (!preg_match('#^' . preg_quote($model->client_id) . '\..+$#', $model->id)) {
            $model->id = $model->client_id . '.' . $model->id;
        }
    }
}
