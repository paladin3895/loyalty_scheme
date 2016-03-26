<?php
namespace App\Models\Observers;

use App\Models\Subscriber;
use App\Models\Event;
use App\Models\Schema;

use App\Exceptions\ExceptionResolver;

class SubscriberObserver
{
    public function creating(Subscriber $model)
    {
        if (!$this->checkMatch($model->event_id, $model->schema_id)) {
            throw ExceptionResolver::resolve('conflict', 'event and schema not match');
        }
    }

    public function updating(Subscriber $model)
    {
        if (!$this->checkMatch($model->event_id, $model->schema_id)) {
            throw ExceptionResolver::resolve('conflict', 'event and schema not match');
        }
    }

    protected function checkMatch($event_id, $schema_id)
    {
        $event = Event::find($event_id);
        $schema = Schema::find($schema_id);
        if ($event && $schema) {
            if ($event->client_id === $schema->client_id) {
                return true;
            }
        }
        return false;
    }
}
