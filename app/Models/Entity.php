<?php

namespace App\Models;

use App\Models\Interfaces\BelongsToClient;

class Entity extends BaseModel implements BelongsToClient
{
    use Traits\DynamicFieldTrait;
    use Traits\BelongsToClient;

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
    protected $fillable = ['fields'];

    protected $staticFields = ['id', 'external_id', 'client_id', 'properties', self::CREATED_AT, self::UPDATED_AT, self::DELETED_AT];

    protected $dynamicField = 'fields';

    protected $casts = [
        'fields' => 'object',
        'properties' => 'array',
    ];

    protected $appends = ['external_id'];

    public function checkpoints()
    {
        return $this->hasMany('App\Models\Checkpoint', 'entity_id', 'id');
    }
}
