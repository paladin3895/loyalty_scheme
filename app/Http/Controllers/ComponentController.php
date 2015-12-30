<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ComponentController extends BaseApiController
{
    public function getPolicy()
    {
        return (\Liquid\Builders\PolicyBuilder::getPolicyFormats());
    }

    public function getReward()
    {
        return (\Liquid\Builders\PolicyBuilder::getRewardFormats());
    }
}
