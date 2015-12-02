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
    protected $fillable = ['policy_id', 'privilege_id', 'policy_config', 'privilege_context'];

    protected $casts = [
        'policy_config' => 'array',
        'privilege_context' => 'array'
    ];

    public function schema()
    {
        return $this->belongsTo('App\Models\Schema', 'schema_id', 'id');
    }

    public function policy()
    {
        return $this->hasOne('App\Models\Policy', 'policy_id', 'id');
    }

    public function privilege()
    {
        return $this->hasOne('App\Models\Privilege', 'privilege_id', 'id');
    }
}
