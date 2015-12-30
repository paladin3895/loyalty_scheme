<?php
namespace App\Formatters;

use League\Fractal\TransformerAbstract;
use App\Models\Node;

class NodeFormatter extends TransformerAbstract
{
    public function transform(Node $node)
    {
        return $node->toArray();
    }
}
