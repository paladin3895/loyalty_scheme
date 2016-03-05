<?php

namespace App\Models;

use App\Models\Interfaces\BelongsToClient;

class Schema extends BaseModel implements BelongsToClient
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

     protected $staticFields = ['id', 'client_id', self::CREATED_AT, self::UPDATED_AT, self::DELETED_AT];

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

    public function setClientIdAttribute($id)
    {
        $this->attributes['client_id'] = (string)$id;
    }

    public function getClientIdAttribute()
    {
        return (string)$this->attributes['client_id'];
    }
}
