<?php

namespace App\Models;

use App\Models\Interfaces\BelongsToClient;
use App\Models\Observers\EventObserver;

class Event extends BaseModel implements BelongsToClient
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'events';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'content', 'condition'];

    protected $casts = [
        'content' => 'array',
        'condition' => 'array',
    ];

    public $incrementing = false;

    // public function schemas()
    // {
    //     return $this->belongsToMany(
    //         'App\Models\Schema',
    //         'event_schema',
    //         'event_id',
    //         'schema_id'
    //     );
    // }

    public static function boot()
    {
        parent::boot();

        self::observe(new EventObserver);
    }

    public function subscribers()
    {
        return $this->hasMany('App\Models\Subscriber', 'event_id', 'id');
    }

    public function setClientIdAttribute($id)
    {
        $this->attributes['client_id'] = (string)$id;
    }

    public function getClientIdAttribute()
    {
        return (string)$this->attributes['client_id'];
    }

    public function scopeBelongsToClient($query, $id)
    {
        return $query->where('client_id', $id);
    }
}
