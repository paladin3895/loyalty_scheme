<?php
namespace App\Formatters;

use League\Fractal\TransformerAbstract;
use App\Models\Link;

class LinkFormatter extends TransformerAbstract
{
    public function transform(Link $link)
    {
        return $link->toArray();
    }
}
