<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PrivilegeController extends SingularController
{
    protected $endpoint = 'privilege';

    protected $repository = 'App\Models\Privilege';
}
