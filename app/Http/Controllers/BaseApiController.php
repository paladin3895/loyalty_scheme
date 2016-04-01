<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Interfaces\BelongsToClient;
use App\Models\Identifier;

class BaseApiController extends BaseController
{
    use Helpers;

    public function find($id)
    {
        if ($this->repository instanceof BelongsToClient) {
            $this->repository = $this->repository->belongsToClient($this->auth->user());
        }

        if (!Identifier::isInternal($id)) {
            $id = Identifier::getInternalId($id, $this->auth->user());
        }
        return $this->repository->find($id);
    }
}
