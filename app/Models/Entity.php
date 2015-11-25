<?php

namespace App\Models;

class Entity extends BaseModel
{

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

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $bucket;

    protected $casts = [
        'attributes' => 'object'
    ];

    public function getAttribute($key) {
        if (!isset($this->bucket)) $this->bucket = json_decode($this->attributes['attributes']);
        return $this->bucket->$key;
    }

    public function setAttribute($key, $value) {
        if (!isset($this->bucket)) $this->bucket = json_decode($this->attributes['attributes']);
        $this->bucket->$key = $value;
        $this->attributes['attributes'] = json_encode($this->bucket);
    }
}
