<?php
namespace App\Models\Traits;

use App\Exception\ExceptionResolver;

trait BelongsToClient
{
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
}
