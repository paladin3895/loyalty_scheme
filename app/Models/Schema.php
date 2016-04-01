<?php

namespace App\Models;

use App\Models\Interfaces\BelongsToClient;

class Schema extends BaseModel implements BelongsToClient
{
    use Traits\DynamicFieldTrait;
    use Traits\BelongsToClient;

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
    protected $fillable = ['fields'];

    protected $staticFields = ['id', 'external_id', 'client_id', self::CREATED_AT, self::UPDATED_AT, self::DELETED_AT];

    protected $dynamicField = 'fields';

    protected $casts = [
         'fields' => 'object',
     ];

    protected $appends = ['external_id'];

    public function nodes()
    {
        return $this->hasMany('App\Models\Node', 'schema_id', 'id');
    }

    public function links()
    {
        return $this->hasMany('App\Models\Link', 'schema_id', 'id');
    }

    public function events()
    {
        return $this->belongsToMany(
            'App\Models\Event',
            'event_schema',
            'schema_id',
            'event_id'
        );
    }
}
