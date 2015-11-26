<?php

namespace App\Models\Relations;

use App\Models\BaseModel;

class EntityPrivilege extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'entity_privilege';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['entity_id', 'privilege_id'];

}
