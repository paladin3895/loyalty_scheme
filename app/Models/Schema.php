<?php

namespace App\Models;

class Schema extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'schemas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];

    public function nodes()
    {
        return $this->hasMany('App\Models\Node', 'schema_id', 'id');
    }

    public function links()
    {
        return $this->hasMany('App\Models\Link', 'schema_id', 'id');
    }
}
