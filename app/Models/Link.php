<?php

namespace App\Models;

use App\Models\Observers\LinkObserver;

class Link extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'links';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['node_from', 'node_to'];

    public static function boot()
    {
        parent::boot();

        self::observe(new LinkObserver);
    }

    public function schema()
    {
        return $this->belongsTo('App\Models\Schema', 'schema_id', 'id');
    }

    // public function getNodeFromAttribute()
    // {
    //     return Node::find($this->attributes['node_from']);
    // }
    //
    // public function getNodeToAttribute()
    // {
    //     return Node::find($this->attributes['node_to']);
    // }

}
