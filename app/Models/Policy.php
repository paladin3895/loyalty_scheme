<?php

namespace App\Models;

class Policy extends BaseModel
{
    use Traits\DynamicFieldTrait;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'policy';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'config'];

    protected $staticFields = ['id', 'name', 'description', self::CREATED_AT, self::UPDATED_AT, self::DELETED_AT];

    protected $dynamicField = 'config';

    protected $casts = [
        'config' => 'object'
    ];
}
