<?php

namespace App\Models;

class Link extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'link';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['node_from', 'node_to'];

    public function schema()
    {
        return $this->belongsTo('App\Models\Schema', 'schema_id', 'id');
    }

    public function getNodeFromAttribute()
    {
        return Node::find($this->attributes['node_from']);
    }

    public function getNodeToAttribute()
    {
        return Node::find($this->attributes['node_to']);
    }
}
