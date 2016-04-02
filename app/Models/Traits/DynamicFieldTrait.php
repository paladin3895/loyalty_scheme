<?php
namespace App\Models\Traits;

use App\Exceptions\ExceptionResolver;

trait DynamicFieldTrait
{
    protected $bucket;

    public function getDynamicField()
    {
        return $this->dynamicField;
    }

    public function getStaticFields()
    {
        return $this->staticFields;
    }

    public function getAttribute($key)
    {
        if (in_array($key, $this->staticFields)) {
            return parent::getAttribute($key);
        } else {
            $this->initBucket();
            if ($key == $this->dynamicField) {
                return $this->bucket;
            }
            return $this->bucket->$key;
        }
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->staticFields)) {
            parent::setAttribute($key, $value);
        } elseif ($key == $this->dynamicField) {
            if (is_scalar($value)) {
                throw ExceptionResolver::resolve(
                    'conflict',
                    "cannot set single value for {$key} because it's a reserved property"
                );
            }
            $this->initBucket();
            $this->bucket = (object)array_merge((array)$this->bucket, (array)$value);
            $this->attributes[$this->dynamicField] = json_encode($this->bucket);
        } else {
            $this->initBucket();
            $this->bucket->$key = $value;
            $this->attributes[$this->dynamicField] = json_encode($this->bucket);
        }
    }

    public function toArray($isGrouped = false)
    {
        $buffer = parent::toArray();

        if ($isGrouped) return $buffer;

        $result = [];
        foreach ($buffer as $key => $value) {
            if ($key != $this->dynamicField) {
                $result[$key] = $value;
            }
        }

        foreach ((array)$buffer[$this->dynamicField] as $key => $value) {
            if (!array_key_exists($key, $result)) {
                $result[$key] = $value;
            } else {
                $result[$this->dynamicField][$key] = $value;
            }
        }

        return $result;
    }

    public function __isset($key)
    {
        if (parent::__isset($key)) return true;
        $this->initBucket();
        return (property_exists($this->bucket, $key));
    }

    protected function initBucket()
    {
        if (!isset($this->bucket)) {
            $dynamicField = isset($this->attributes[$this->dynamicField]) ?
                $this->attributes[$this->dynamicField] : '{}';
            $this->bucket = (object)json_decode($dynamicField);
        }
    }
}
