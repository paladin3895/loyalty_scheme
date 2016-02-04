<?php

namespace App\Models;

class Schema extends BaseModel
{
    use Traits\DynamicFieldTrait;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'schemas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['attributes'];

     protected $staticFields = ['id', self::CREATED_AT, self::UPDATED_AT, self::DELETED_AT];

     protected $dynamicField = 'attributes';

     protected $casts = [
         'attributes' => 'object',
     ];

    public function nodes()
    {
        return $this->hasMany('App\Models\Node', 'schema_id', 'id');
    }

    public function links()
    {
        return $this->hasMany('App\Models\Link', 'schema_id', 'id');
    }
}
