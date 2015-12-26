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
    protected $fillable = ['name', 'description'];

    public function node()
    {
        return $this->hasMany('App\Models\Node', 'schema_id', 'id');
    }

    public function link()
    {
        return $this->hasMany('App\Models\Link', 'schema_id', 'id');
    }
}
