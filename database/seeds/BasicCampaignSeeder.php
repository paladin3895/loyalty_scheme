<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Entity;
use App\Models\Schema;
use App\Models\Event;

class BasicCampaignSeeder extends Seeder
{
    protected $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client([
            // Base URI is used with relative requests
            'base_uri' => 'http://liquid.dev/api/v1/'
        ]);
    }

    protected function authorize(array $scopes = ['read','edit','execute'])
    {
        $res = $this->client->post('oauth', [
            'json' => [
                'grant_type' => 'client_credentials',
                'client_id' => env('APP_ID'),
                'client_secret' => env('APP_SECRET'),
                'scope' => implode(',', $scopes),
            ],
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]);
        $result = json_decode($res->getBody());
        return $result->access_token;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accessToken = $this->authorize();

        $schema_login = $this->createSchemaUserLogin($accessToken);
        $schema_posting = $this->createSchemaUserPosting($accessToken);
        $schema_level = $this->createSchemaUserLevel($accessToken);

        $event_init = $this->createEventUserInit($accessToken);
        $event_login = $this->createEventUserLogin($accessToken, [$schema_login]);
        $event_posting = $this->createEventUserPosting($accessToken, [$schema_posting]);
    }

    protected function createSchemaUserLogin($accessToken)
    {
        $schema = json_decode($this->client->post('schemas', [
            'json' => [
                'data' => [
                    'external_id' => 'schema.user.login',
                    'name' => 'User login',
                    'description' => 'Schema for daily user login',
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ])->getBody())->data;

        $node_1 = json_decode($this->client->post("schema/{$schema->id}/nodes", [
            'json' => [
                'data' => [
                    'config' => [
                        'class' => 'Liquid\\Processors\\CycleProcessor',
                        'number' => 0,
                    ],
                    'policies' => [
                        'check_login_increment' => [
                            'class' => "Liquid\\Processors\\Units\\Policies\\CheckValueIncrement",
                            'attribute' => 'login',
                            'increment' => 1,
                            'name' => 'check_login_increment',
                        ]
                    ],
                    'rewards' => [
                        'r1' => [
                            'class' => 'Liquid\\Processors\\Units\\Rewards\\AddValueReward',
                            'attribute' => 'point',
                            'value' => '${point} + ${level}'
                        ]
                    ],
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ])->getBody())->data;

        $node_2 = json_decode($this->client->post("schema/{$schema->id}/nodes", [
            'json' => [
                'data' => [
                    'config' => [
                        'class' => 'Liquid\\Processors\\CycleProcessor',
                        'number' => 0,
                    ],
                    'policies' => [
                        'check_login_increment' => [
                            'class' => "Liquid\\Processors\\Units\\Policies\\CheckConsecutiveRepeat",
                            'attribute' => 'login',
                            'repeat' => 2,
                            'name' => 'check_login_consecutive',
                        ]
                    ],
                    'rewards' => [
                        'r1' => [
                            'class' => 'Liquid\\Processors\\Units\\Rewards\\AddValueReward',
                            'attribute' => 'point',
                            'value' => '${point} + ${level}'
                        ]
                    ],
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ])->getBody())->data;

        $link = $this->client->post("schema/{$schema->id}/links", [
            'json' => [
                'data' => [
                    'node_from' => $node_1->id,
                    'node_to' => $node_2->id,
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        return $schema;
    }

    protected function createSchemaUserPosting($accessToken)
    {
        $schema = json_decode($this->client->post('schemas', [
            'json' => [
                'data' => [
                    'external_id' => 'schema.user.posting',
                    'name' => 'User posting property',
                    'description' => 'Schema for daily user posting',
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ])->getBody())->data;

        $node_1 = json_decode($this->client->post("schema/{$schema->id}/nodes", [
            'json' => [
                'data' => [
                    'config' => [
                        'class' => 'Liquid\\Processors\\CycleProcessor',
                        'number' => 0,
                    ],
                    'policies' => [
                        'check_posting_increment' => [
                            'class' => "Liquid\\Processors\\Units\\Policies\\CheckValueIncrement",
                            'attribute' => 'posting',
                            'increment' => 1,
                            'name' => 'check_posting_increment',
                        ]
                    ],
                    'rewards' => [
                        'r1' => [
                            'class' => 'Liquid\\Processors\\Units\\Rewards\\AddValueReward',
                            'attribute' => 'point',
                            'value' => '${point} + ${level}*5'
                        ]
                    ],
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ])->getBody())->data;

        $node_2 = json_decode($this->client->post("schema/{$schema->id}/nodes", [
            'json' => [
                'data' => [
                    'config' => [
                        'class' => 'Liquid\\Processors\\CycleProcessor',
                        'number' => 0,
                    ],
                    'policies' => [
                        'check_posting_increment' => [
                            'class' => "Liquid\\Processors\\Units\\Policies\\CheckConsecutiveRepeat",
                            'attribute' => 'posting',
                            'repeat' => 2,
                            'name' => 'check_posting_consecutive',
                        ]
                    ],
                    'rewards' => [
                        'r1' => [
                            'class' => 'Liquid\\Processors\\Units\\Rewards\\AddValueReward',
                            'attribute' => 'point',
                            'value' => '${point} + ${level}*5'
                        ]
                    ],
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ])->getBody())->data;

        $link = $this->client->post("schema/{$schema->id}/links", [
            'json' => [
                'data' => [
                    'node_from' => $node_1->id,
                    'node_to' => $node_2->id,
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        return $schema;
    }

    protected function createSchemaUserLevel($accessToken)
    {
        $schema = json_decode($this->client->post('schemas', [
            'json' => [
                'data' => [
                    'external_id' => 'schema.user.level',
                    'name' => 'User level',
                    'description' => 'Schema for user level',
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ])->getBody())->data;

        $node_1 = json_decode($this->client->post("schema/{$schema->id}/nodes", [
            'json' => [
                'data' => [
                    'config' => [
                        'class' => 'Liquid\\Processors\\CycleProcessor',
                        'number' => 0,
                    ],
                    'policies' => [
                        'check_login_increment' => [
                            'class' => "Liquid\\Processors\\Units\\Policies\\CheckValueIncrement",
                            'attribute' => 'login',
                            'increment' => 1,
                            'name' => 'check_login_increment',
                        ]
                    ],
                    'rewards' => [
                        'r1' => [
                            'class' => 'Liquid\\Processors\\Units\\Rewards\\AddValueReward',
                            'attribute' => 'point',
                            'value' => '${point} + ${level}'
                        ]
                    ],
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ])->getBody())->data;

        $node_2 = json_decode($this->client->post("schema/{$schema->id}/nodes", [
            'json' => [
                'data' => [
                    'config' => [
                        'class' => 'Liquid\\Processors\\CycleProcessor',
                        'number' => 0,
                    ],
                    'policies' => [
                        'check_login_increment' => [
                            'class' => "Liquid\\Processors\\Units\\Policies\\CheckConsecutiveRepeat",
                            'attribute' => 'login',
                            'repeat' => 2,
                            'name' => 'check_login_consecutive',
                        ]
                    ],
                    'rewards' => [
                        'r1' => [
                            'class' => 'Liquid\\Processors\\Units\\Rewards\\AddValueReward',
                            'attribute' => 'point',
                            'value' => '${point} + ${level}'
                        ]
                    ],
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ])->getBody())->data;

        $link = $this->client->post("schema/{$schema->id}/links", [
            'json' => [
                'data' => [
                    'node_from' => $node_1->id,
                    'node_to' => $node_2->id,
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        return $schema;
    }

    protected function createEventUserLogin($accessToken, array $schemas)
    {
        $event = json_decode($this->client->post('events', [
            'json' => [
                'data' => [
                    'external_id' => 'event.user.login',
                    'name' => 'User login event',
                    'description' => 'Event for daily user login',
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ])->getBody())->data;

        $priority = 0;
        foreach ($schemas as $schema) {
            $this->client->post("event/{$event->id}", [
                'json' => [
                    'data' => [
                        'schema_id' => $schema->id,
                        'priority' => $priority++,
                    ],
                ],
                'headers' => [
                    'Authorization' => "Bearer {$accessToken}",
                    'Accept' => 'application/json',
                ],
            ]);
        }

        return $event;
    }

    protected function createEventUserPosting($accessToken, array $schemas)
    {
        $event = json_decode($this->client->post('events', [
            'json' => [
                'data' => [
                    'external_id' => 'event.user.posting',
                    'name' => 'User posting event',
                    'description' => 'Event for daily user posting',
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ])->getBody())->data;

        $priority = 0;
        foreach ($schemas as $schema) {
            $this->client->post("event/{$event->id}", [
                'json' => [
                    'data' => [
                        'schema_id' => $schema->id,
                        'priority' => $priority++,
                    ],
                ],
                'headers' => [
                    'Authorization' => "Bearer {$accessToken}",
                    'Accept' => 'application/json',
                ],
            ]);
        }

        return $event;
    }
}
