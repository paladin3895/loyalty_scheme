<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Formatters\ArrayFormatter;

class ComponentController extends BaseApiController
{
    public function getPolicies()
    {
        return $this->response->array([
            'status' => 1,
            'data' => \Liquid\Builders\PolicyBuilder::getPolicyFormats()
        ]);
    }

    public function getRewards()
    {
        return $this->response->array([
            'status' => 1,
            'data' => \Liquid\Builders\PolicyBuilder::getRewardFormats()
        ]);
    }

    public function getProcessors()
    {
        return $this->response->array([
            'status' => 1,
            'data' => \Liquid\Builders\PolicyBuilder::getProcessorFormats()
        ]);
    }
}
