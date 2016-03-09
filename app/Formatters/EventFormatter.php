<?php
namespace App\Formatters;

use App\Formatters\SubscriberFormatter;
use App\Models\Event;

class EventFormatter extends ModelFormatter
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'subscribers'
    ];

    public function includeSubscribers(Event $event)
    {
        return $this->collection($event->subscribers()->get(), new SubscriberFormatter);
    }
}
