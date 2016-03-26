<?php
namespace App\Models\Observers;

use App\Models\Schema;
use App\Models\Node;
use App\Models\Link;

use App\Exceptions\ExceptionResolver;

class LinkObserver
{
    public function saving(Link $model)
    {
        if (!$this->checkMatch($model->schema_id, [$model->node_from, $model->node_to])) {
            throw ExceptionResolver::resolve('conflict', 'schema and nodes not match');
        }

        $linkPool = Schema::find($model->schema_id)->links()->get()->toArray();
        $link = $model->toArray();

        if (!$this->checkDuplicate($link, $linkPool)) {
            throw ExceptionResolver::resolve('conflict', 'there are duplicate links');
        }

        if (!$this->checkCircular($link, $linkPool)) {
            throw ExceptionResolver::resolve('conflict', 'there is circular flow');
        }
    }

    protected function checkMatch($schema_id, array $node_ids)
    {
        foreach ($node_ids as $node_id) {
            $node = Node::find($node_id);
            if (!isset($node) || ($node->schema_id !== $schema_id)) return false;
        }
        return true;
    }

    protected function checkDuplicate(array $link, array $pool)
    {
        // @TODO: implement logic to detect duplicate here
        return true;
    }

    protected function checkCircular(array $link, array $pool)
    {
        // @TODO: implement logic to detect circular here
        return true;
    }
}
