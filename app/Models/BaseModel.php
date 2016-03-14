<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    const DELETED_AT = 'deleted_at';

    /**
     * The dynamic connection config at runtime
     * @var string
     */
    protected static $globalConnection;

    public static function setGlobalConnection($connection)
    {
        static::$globalConnection = $connection;
    }


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [self::CREATED_AT, self::UPDATED_AT, self::DELETED_AT, 'pivot'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (static::$globalConnection) {
            $this->setConnection(static::$globalConnection);
        } elseif (self::$globalConnection) {
            $this->setConnection(static::$globalConnection);
        }
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
    }

    public function clear()
    {
        foreach ($this->fillable as $key) {
            $this->attributes[$key] = null;
        }
        return $this;
    }
}
