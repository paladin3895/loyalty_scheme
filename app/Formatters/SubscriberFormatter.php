<?php
namespace App\Formatters;

use App\Models\BaseModel;
use App\Models\Subscriber;

class SubscriberFormatter extends ModelFormatter
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'event', 'schema'
    ];

    protected function includeSchema(Subscriber $subscriber)
    {
        return $this->item($subscriber->schema()->first(), new SchemaFormatter);
    }

    protected function includeEvent(Subscriber $subscriber)
    {
        return $this->item($subscriber->event()->first(), new EventFormatter);
    }
}
