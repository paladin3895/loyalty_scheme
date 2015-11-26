<?php

namespace App\Models;

class Schema extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'schema';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'nodes', 'links'];

    protected $casts = [
        'nodes' => 'array',
        'links' => 'array',
    ];

    public function getPoliciesAttribute()
    {
        // TODO: implement logic here to retrieve policies
    }
}
