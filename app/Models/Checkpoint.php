<?php

namespace App\Models;

class Checkpoint extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'checkpoints';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['entity_id', 'schema_id', 'state'];

    protected $casts = [
        'state' => 'array'
    ];

    public function entity()
    {
        return $this->belongsTo('App\Models\Entity');
    }
}
