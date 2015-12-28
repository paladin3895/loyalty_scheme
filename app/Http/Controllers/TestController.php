<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Liquid\Builders\PolicyBuilder;
use Liquid\Records\Record;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TestController extends BaseController
{
    public function test()
    {
        $schema = \App\Models\Schema::with(['node', 'link'])->find(1);
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
        $registry->process(new Record([
            'name' => 'come-stay',
            'point' => 30,
        ]));
        dd(Record::$history);
    }
}
