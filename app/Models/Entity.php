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
    protected $fillable = ['attributes'];

    protected $staticFields = ['id', 'client_id', 'properties', self::CREATED_AT, self::UPDATED_AT, self::DELETED_AT];

    protected $dynamicField = 'attributes';

    protected $casts = [
        'attributes' => 'object',
        'properties' => 'array',
    ];

    public function checkpoints()
    {
        return $this->hasMany('App\Models\Checkpoint', 'entity_id', 'id');
    }
}
