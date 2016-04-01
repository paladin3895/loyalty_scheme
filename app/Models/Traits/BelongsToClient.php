<?php
namespace App\Models\Traits;

use App\Models\Identifier;
use App\Models\Interfaces\BelongsToClient as BelongsToClientInterface;
use App\Exception\ExceptionResolver;

trait BelongsToClient
{
    protected $externalId;

    public function setExternalIdAttribute($id)
    {
        $this->externalId = "{$this->client_id}.{$id}";
    }

    public function getExternalIdAttribute()
    {
        if (!$this->externalId) {
            $type = last(explode('\\', get_called_class()));
            $this->externalId = Identifier::getExternalId($this->id, $type);
        }
        return str_replace("{$this->client_id}.", '', $this->externalId);
    }

    public function setClientIdAttribute($id)
    {
        $this->attributes['client_id'] = (string)$id;
    }

    public function getClientIdAttribute()
    {
        return (string)$this->attributes['client_id'];
    }

    public function scopeBelongsToClient($query, $id)
    {
        return $query->where('client_id', $id);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function (BelongsToClientInterface $model) {
            $type = last(explode('\\', get_class($model)));
            if (!$model->external_id) {
                $model->external_id = Identifier::generate($model->id, $type);
            }
            Identifier::create([
                'external_id' => $model->client_id . '.' . $model->external_id,
                'internal_id' => $model->id,
                'type' => $type,
            ]);
        });

        static::deleting(function (BelongsToClientInterface $model) {
            $type = last(explode('\\', get_class($model)));
            Identifier::where('internal_id', $model->id)
                      ->where('type', $type)
                      ->delete();
        });
    }
}
