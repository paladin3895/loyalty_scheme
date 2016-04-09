<?php
namespace App\Http;

use App\Models\Schema;
use App\Models\Entity;
use App\Models\Checkpoint;

class Helpers
{
    public static function buildSchema(Schema $schema)
    {
        $registry = (new \Liquid\Schema(config('liquid')))->build(
            $schema->nodes()->get()->toArray(),
            $schema->links()->get()->toArray()
        );
        $registry->initialize();
        return $registry;
    }

    public static function prepareEntityRecord(Entity $entity, Checkpoint $checkpoint)
    {
        $buffer = $entity->toArray(true);
        $data = [];

        $data = array_merge((array)$buffer['properties'], (array)$buffer['fields']);
        $record = new \Liquid\Records\Record($data);
        \Liquid\Records\Record::remember((array)$checkpoint->state);
        return $record;
    }

    public static function plural($endpoint)
    {
        switch ($endpoint) {
            case 'entity'    : return 'entities';
            case 'schema'    : return 'schemas';
            case 'event'     : return 'events';
            case 'checkpoint': return 'checkpoints';
            case 'subscriber': return 'subscribers';
            case 'policy'    : return 'policies';
            case 'reward'    : return 'rewards';
            case 'node'      : return 'nodes';
            case 'link'      : return 'links';
            default          : return $endpoint;
        }
    }

    public static function singular($endpoint)
    {
        switch ($endpoint) {
            case 'entities'   : return 'entity';
            case 'schemas'    : return 'schema';
            case 'events'     : return 'event';
            case 'checkpoints': return 'checkpoint';
            case 'subscribers': return 'subscriber';
            case 'policies'   : return 'policy';
            case 'rewards'    : return 'reward';
            case 'nodes'      : return 'node';
            case 'links'      : return 'link';
            default           : return $endpoint;
        }
    }

    public static function policyMerge(array $merging, array $merged)
    {
        foreach ($merged as $key => $value) {
            if (!array_key_exists($key, $merging)) {
                $merging[$key] = $value;
            } else {
                $merging[$key] = self::policyCompute($merging[$key], $value);
            }
        }
        return $merging;
    }

    public static function policyCompute($value, $delta)
    {
        if (!isset($value)) {
            return $delta;
        } else {
            /* */ if (is_array($value)) {
                return array_merge($delta, $value);
            } elseif (is_object($value)) {
                return (object)array_merge((array)$delta, (array)$value);
            } elseif (is_string($value)) {
                return (string)$delta;
            } elseif (is_numeric($value)) {
                return $value + $delta;
            } elseif (is_bool($value)) {
                return (boolean)$delta;
            }
        }
    }
}
