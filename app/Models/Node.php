<?php

namespace App\Models;

class Node extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'nodes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['policies', 'rewards'];

    protected $casts = [
        'policies' => 'array',
        'rewards' => 'array'
    ];

    public function schema()
    {
        return $this->belongsTo('App\Models\Schema', 'schema_id', 'id');
    }
}
