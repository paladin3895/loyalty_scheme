<?php
namespace App\Formatters;

use League\Fractal\TransformerAbstract;
use App\Models\Entity;

class EntityFormatter extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'checkpoints'
    ];
    
    public function transform(Entity $entity)
    {
        return $entity->toArray();
    }
}
