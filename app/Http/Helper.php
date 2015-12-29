<?php
namespace App\Http;

use App\Models\Schema;

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
}
