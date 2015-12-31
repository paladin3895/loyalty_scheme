<?php
namespace App\Formatters;

use Dingo\Api\Transformer\Adapter\Fractal;
use Dingo\Api\Transformer\Binding;
use Dingo\Api\Http\Request;

class Adapter extends Fractal
{
    public function transform($response, $transformer, Binding $binding, Request $request)
    {
        $results['status'] = 1;
        $results += parent::transform($response, $transformer, $binding, $request);
        return $results;
    }
}
