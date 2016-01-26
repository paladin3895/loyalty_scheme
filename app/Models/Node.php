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
    protected $fillable = ['policies', 'rewards', 'config'];

    protected $casts = [
        'policies' => 'array',
        'rewards' => 'array',
        'config' => 'object',
    ];

    protected $staticFields = ['id', 'schema_id', self::CREATED_AT, self::UPDATED_AT, self::DELETED_AT, 'policies', 'rewards'];

    protected $dynamicField = 'config';

    public function schema()
    {
        return $this->belongsTo('App\Models\Schema', 'schema_id', 'id');
    }
}
