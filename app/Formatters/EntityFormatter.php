<?php
namespace App\Formatters;

use App\Formatters\ModelFormatter;
use App\Models\Entity;

class EntityFormatter extends ModelFormatter
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'checkpoints'
    ];

    public function includeCheckpoints(Entity $entity)
    {
        return $this->collection($entity->checkpoints()->get(), new ModelFormatter);
    }
}
