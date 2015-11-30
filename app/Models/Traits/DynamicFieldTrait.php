<?php
namespace App\Models\Traits;

trait DynamicFieldTrait
{
    protected $bucket;

    public function getAttribute($key)
    {
        if (!in_array($key, $this->staticFields)) {
            if (!isset($this->bucket)) $this->bucket = json_decode($this->attributes[$this->dynamicField]);
            return $this->bucket->$key;
        } else {
            return parent::getAttribute($key);
        }
    }

    public function setAttribute($key, $value)
    {
        if (!in_array($key, $this->staticFields)) {
            $dynamicField = isset($this->attributes[$this->dynamicField]) ?
                $this->attributes[$this->dynamicField] : '{}';
            if (!isset($this->bucket)) $this->bucket = json_decode($dynamicField);
            $this->bucket->$key = $value;
            $this->attributes[$this->dynamicField] = json_encode($this->bucket);
        } else {
            parent::setAttribute($key, $value);
        }
    }

    public function clearDynamicFields()
    {
        $this->attributes[$this->dynamicField] = '{}';
        $this->bucket = null;
    }
}
