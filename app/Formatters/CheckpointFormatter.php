<?php
namespace App\Formatters;

use App\Models\Checkpoint;

class CheckpointFormatter extends ModelFormatter
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [];

}
