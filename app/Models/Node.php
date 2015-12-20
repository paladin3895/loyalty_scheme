<?php

namespace App\Models;

class Node extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'node';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['policy', 'reward'];

    protected $casts = [
        'policy' => 'array',
        'reward' => 'array'
    ];

    public function schema()
    {
        return $this->belongsTo('App\Models\Schema', 'schema_id', 'id');
    }
}
