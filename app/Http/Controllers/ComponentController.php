<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ComponentController extends BaseApiController
{
    public function getPolicies()
    {
        return (\Liquid\Builders\PolicyBuilder::getPolicyFormats());
    }

    public function getRewards()
    {
        return (\Liquid\Builders\PolicyBuilder::getRewardFormats());
    }
}
