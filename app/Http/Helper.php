<?php
namespace App\Http;

use App\Models\Schema;
use App\Models\Entity;
use App\Models\Checkpoint;

class Helper
{
    public static function buildSchema(Schema $schema)
    {
        $registry = new \Liquid\Registry();
        $nodes = [];
        foreach ($schema->nodes as $node) {
            $policy = (new \Liquid\Builders\PolicyBuilder)->make($node->toArray());
            $nodes[$node->id] = new \Liquid\Nodes\PolicyNode($node->id);
            $nodes[$node->id]->bind($policy);
            $nodes[$node->id]->register($registry);
        }
        foreach ($schema->links as $link) {
            if (isset($nodes[$link->node_from]) && isset($nodes[$link->node_to])) {
                $nodes[$link->node_from]->forward($nodes[$link->node_to]);
            }
        }
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
            case 'checkpoint': return 'checkpoints';
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
            case 'checkpoints': return 'checkpoint';
            case 'policies'   : return 'policy';
            case 'rewards'    : return 'reward';
            case 'nodes'      : return 'node';
            case 'links'      : return 'link';
            default           : return $endpoint;
        }
    }
}
