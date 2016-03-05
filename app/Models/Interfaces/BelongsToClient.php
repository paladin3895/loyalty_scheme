<?php
namespace App\Models\Interfaces;

interface BelongsToClient
{
    public function setClientIdAttribute($id);

    public function getClientIdAttribute();
}
