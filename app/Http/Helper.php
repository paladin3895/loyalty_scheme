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
        foreach ($schema->node as $node) {
            $policy = (new \Liquid\Builders\PolicyBuilder)->make($node->toArray());
            $nodes[$node->id] = new \Liquid\Nodes\PolicyNode($node->id);
            $nodes[$node->id]->bind($policy);
            $nodes[$node->id]->register($registry);
        }
        foreach ($schema->link as $link) {
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
            case 'entity': return 'entities';
            case 'schema': return 'schemas';
            case 'policy': return 'policies';
            case 'reward': return 'rewards';
            case 'node'  : return 'nodes';
            case 'link'  : return 'links';
            default      : return "{$endpoint}s";
        }
    }
}
