<?php
namespace App\Formatters;

use App\Models\Link;

class LinkFormatter extends ModelFormatter
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [];

}
