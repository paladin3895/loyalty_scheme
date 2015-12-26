<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RewardController extends BaseController
{
    public function index()
    {
        return (\Liquid\Builders\PolicyBuilder::getRewardFormats());
    }
}
