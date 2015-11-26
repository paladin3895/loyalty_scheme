<?php

namespace App\Models;

class Privilege extends BaseModel
{
    use Traits\DynamicFieldTrait;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'privilege';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'type', 'context'];

    protected $staticFields = ['id', 'name', 'description', 'type', self::CREATED_AT, self::UPDATED_AT, self::DELETED_AT];

    protected $dynamicField = 'context';

    protected $casts = [
        'context' => 'object'
    ];
}
