<?php

namespace App\Models;

class Entity extends BaseModel
{
    use Traits\DynamicFieldTrait;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'entity';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['attributes'];

    protected $staticFields = ['id', self::CREATED_AT, self::UPDATED_AT, self::DELETED_AT];

    protected $dynamicField = 'attributes';

    protected $casts = [
        'attributes' => 'object'
    ];

    public function checkpoints()
    {
        return $this->hasMany('App\Models\Checkpoint', 'entity_id', 'id');
    }

    public function privileges()
    {
        return $this->belongsToMany('App\Models\Privilege', 'entity_privilege', 'entity_id', 'privilege_id');
    }
}
