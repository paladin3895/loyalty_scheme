<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PolicyController extends ApiController
{
    protected $endpoint = 'policy';

    protected $repository = 'App\Models\Policy';

    public function unitComponents()
    {
        return (\Liquid\Builders\UnitBuilder::getFormats());
    }

    public function algorithmComponents()
    {
        return (\Liquid\Builders\AlgorithmBuilder::getFormats());
    }
}
