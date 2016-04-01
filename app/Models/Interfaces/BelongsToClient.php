<?php
namespace App\Models\Interfaces;

interface BelongsToClient
{
    public function setExternalIdAttribute($id);

    public function getExternalIdAttribute();
    
    public function setClientIdAttribute($id);

    public function getClientIdAttribute();

    public function scopeBelongsToClient($query, $id);
}
