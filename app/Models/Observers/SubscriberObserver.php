<?php
namespace App\Models\Observers;

use App\Models\Subscriber;
use App\Models\Event;
use App\Models\Schema;
use App\Models\Identifier;

use App\Exceptions\ExceptionResolver;

class SubscriberObserver
{
    public function saving(Subscriber $model)
    {
        if (!$this->checkMatch($model->event_id, $model->schema_id)) {
            throw ExceptionResolver::resolve('conflict', 'event and schema not match');
        }
    }

    protected function checkMatch($event_id, $schema_id)
    {
        if (!Identifier::isInternal($event_id)) {
            $event_id = Identifier::getInternalId($event_id, \Auth::user());
        }
        $event = Event::find($event_id);

        if (!Identifier::isInternal($schema_id)) {
            $schema_id = Identifier::getInternalId($schema_id, \Auth::user());
        }
        $schema = Schema::find($schema_id);
        if ($event && $schema) {
            if ($event->client_id === $schema->client_id) {
                return true;
            }
        }
        return false;
    }
}
