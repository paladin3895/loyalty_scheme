<?php

namespace App\Models;

use App\Exceptions\ExceptionResolver;

class Identifier extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'master_index';

    protected $primaryKey = 'external_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['external_id', 'internal_id', 'type'];

    protected $casts = [
        'external_id' => 'string',
        'internal_id' => 'integer',
        'type' => 'string',
    ];

    public $incrementing = false;

    protected $clientId;

    public function setClientId($id)
    {
        $this->clientId = $id;
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function setId($id)
    {
        $this->attributes['external_id'] = "{$this->clientId}.{$id}";
    }

    public function getId()
    {
        return str_replace("{$this->clientId}.", '', $this->attributes['external_id']);
    }

    public static function isInternal($id)
    {
        return ((string)(int)$id === (string)$id);
    }

    public static function getInternalId($external_id, $client_id)
    {
        $id = "{$client_id}.{$external_id}";
        $record = self::find($id);
        if (!$record) {
            throw ExceptionResolver::resolve('not found', 'external id not exists');
        }

        return $record->internal_id;
    }

    public static function getExternalId($internal_id, $type)
    {
        $identifier = Identifier::where('internal_id', $internal_id)
                                ->where('type', $type)
                                ->first();
        return isset($identifier) ? $identifier->external_id : null;
    }

    public static function generate($id, $type)
    {
        return strtolower($type . '_' . $id);
    }
}
