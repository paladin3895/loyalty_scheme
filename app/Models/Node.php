<?php

namespace App\Models;

class Node extends BaseModel
{
    use Traits\DynamicFieldTrait;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'nodes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['policies', 'rewards', 'config', 'fields'];

    protected $staticFields = ['id', 'schema_id', 'config', 'policies', 'rewards',
        self::CREATED_AT, self::UPDATED_AT, self::DELETED_AT,];

    protected $dynamicField = 'fields';

    protected $casts = [
        'policies' => 'array',
        'rewards' => 'array',
        'config' => 'object',
        'fields' => 'object',
    ];

    public function schema()
    {
        return $this->belongsTo('App\Models\Schema', 'schema_id', 'id');
    }
}
