<?php
namespace App\Http;

use App\Models\Schema;
use App\Models\Entity;
use App\Models\Checkpoint;

class Helpers
{
    public static function buildSchema(Schema $schema)
    {
        $registry = (new \Liquid\Schema(config('liquid')))->build(
            $schema->nodes()->get()->toArray(),
            $schema->links()->get()->toArray()
        );
        $registry->initialize();
        return $registry;
    }

    public static function prepareEntityRecord(Entity $entity, Checkpoint $checkpoint)
    {
        $data = $entity->toArray();
        $data = array_merge((array)$data['properties'], (array)$data['attributes']);
        $record = new \Liquid\Records\Record((array)$data, (array)$checkpoint->state);
        return $record;
    }

    public static function plural($endpoint)
    {
        switch ($endpoint) {
            case 'entity'    : return 'entities';
            case 'schema'    : return 'schemas';
            case 'event'     : return 'events';
            case 'checkpoint': return 'checkpoints';
            case 'subscriber': return 'subscribers';
            case 'policy'    : return 'policies';
            case 'reward'    : return 'rewards';
            case 'node'      : return 'nodes';
            case 'link'      : return 'links';
            default          : return $endpoint;
        }
    }

    public static function singular($endpoint)
    {
        switch ($endpoint) {
            case 'entities'   : return 'entity';
            case 'schemas'    : return 'schema';
            case 'events'     : return 'event';
            case 'checkpoints': return 'checkpoint';
            case 'subscribers': return 'subscriber';
            case 'policies'   : return 'policy';
            case 'rewards'    : return 'reward';
            case 'nodes'      : return 'node';
            case 'links'      : return 'link';
            default           : return $endpoint;
        }
    }
}
