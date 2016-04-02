<?php

namespace App\Models;

use App\Models\Interfaces\BelongsToClient;
use App\Models\Observers\EventObserver;

class Event extends BaseModel implements BelongsToClient
{
    use Traits\BelongsToClient;

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
    protected $fillable = ['content', 'condition'];

    protected $casts = [
        'content' => 'array',
        'condition' => 'array',
    ];

    // public function schemas()
    // {
    //     return $this->belongsToMany(
    //         'App\Models\Schema',
    //         'event_schema',
    //         'event_id',
    //         'schema_id'
    //     );
    // }

    public function subscribers()
    {
        return $this->hasMany('App\Models\Subscriber', 'event_id', 'id');
    }
}
