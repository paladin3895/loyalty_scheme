<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PrivilegeController extends ApiController
{
    protected $endpoint = 'privilege';

    protected $validations = [];

    protected $repository = 'App\Models\Privilege';
}
