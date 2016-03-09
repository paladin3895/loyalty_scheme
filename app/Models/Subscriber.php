<?php

namespace App\Models;

class Subscriber extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'event_schema';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['event_id', 'schema_id', 'priority'];

    protected $casts = [
        'event_id' => 'string',
        'schema_id' => 'integer',
        'priority' => 'integer',
    ];

    public function schema()
    {
        return $this->belongsTo('App\Models\Schema', 'schema_id', 'id');
    }

    public function event()
    {
        return $this->belongsTo('App\Models\Event', 'event_id', 'id');
    }
}
