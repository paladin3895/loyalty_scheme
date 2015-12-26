<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Liquid\Builders\PolicyBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TestController extends BaseController
{
    public function test()
    {
        $schema = \App\Models\Schema::with(['node', 'link'])->find(2);
        $registry = new \Liquid\Registry();
        $nodes = [];
        foreach ($schema->node as $node) {
            $policy = (new \Liquid\Builders\PolicyBuilder)->make($node->toArray());
            $nodes[$node->id] = new \Liquid\Nodes\Node($node->id);
            $nodes[$node->id]->bind($policy);
            $nodes[$node->id]->register($registry);
        }

        foreach ($schema->link as $link) {
            if (isset($nodes[$link->node_from]) && isset($nodes[$link->node_to])) {
                $nodes[$link->node_from]->forward($nodes[$link->node_to]);
            }
        }

        $registry->initialize();
        dd($registry->process([
            'name' => 'come-stay',
            'point' => 30,
        ]));
    }
}
