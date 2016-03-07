<?php

namespace App\Models;

use App\Models\Interfaces\BelongsToClient;

class Entity extends BaseModel implements BelongsToClient
{
    use Traits\DynamicFieldTrait;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'entities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['attributes'];

    protected $staticFields = ['id', 'client_id', self::CREATED_AT, self::UPDATED_AT, self::DELETED_AT];

    protected $dynamicField = 'attributes';

    protected $casts = [
        'attributes' => 'object',
        'properties' => 'array',
    ];

    public function checkpoints()
    {
        return $this->hasMany('App\Models\Checkpoint', 'entity_id', 'id');
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
