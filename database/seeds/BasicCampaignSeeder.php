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
        $schema_sharing = $this->createSchemaUserSharing($accessToken);
        $schema_rating = $this->createSchemaUserRating($accessToken);
        $schema_comment = $this->createSchemaUserComment($accessToken);
        $schema_level = $this->createSchemaUserLevel($accessToken);

        $event_init = $this->createEventUserInit($accessToken);
        $event_login = $this->createEventUserLogin(
            $accessToken,
            [ $schema_login, $schema_level ]
        );
        $event_posting = $this->createEventUserPosting(
            $accessToken,
            [ $schema_posting, $schema_level ]
        );
        $event_sharing = $this->createEventUserSharing(
            $accessToken,
            [ $schema_sharing, $schema_level ]
        );
        $event_rating = $this->createEventUserRating(
            $accessToken,
            [ $schema_rating, $schema_level ]
        );
        $event_comment = $this->createEventUserComment(
            $accessToken,
            [ $schema_comment, $schema_level ]
        );
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
                            'value' => '${point} + ${level} + 1'
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
                            'value' => '${point} + ${level} + 1'
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
                            'value' => '${point} + ${level}*5 + 5'
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
                            'value' => '${point} + ${level}*5 + 5'
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

    protected function createSchemaUserSharing($accessToken)
    {
        $schema = json_decode($this->client->post('schemas', [
            'json' => [
                'data' => [
                    'external_id' => 'schema.user.sharing',
                    'name' => 'User sharing property',
                    'description' => 'Schema for daily user sharing',
                    'content' => [
                        'sharing' => 1,
                    ],
                    'condition' => [
                        'category' => 'person',
                    ],
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
                        'check_sharing_increment' => [
                            'class' => "Liquid\\Processors\\Units\\Policies\\CheckValueIncrement",
                            'attribute' => 'sharing',
                            'increment' => 1,
                            'name' => 'check_sharing_increment',
                        ]
                    ],
                    'rewards' => [
                        'r1' => [
                            'class' => 'Liquid\\Processors\\Units\\Rewards\\AddValueReward',
                            'attribute' => 'point',
                            'value' => '${point} + ${level}*3 + 3'
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
                        'check_sharing_increment' => [
                            'class' => "Liquid\\Processors\\Units\\Policies\\CheckConsecutiveRepeat",
                            'attribute' => 'sharing',
                            'repeat' => 2,
                            'name' => 'check_sharing_consecutive',
                        ]
                    ],
                    'rewards' => [
                        'r1' => [
                            'class' => 'Liquid\\Processors\\Units\\Rewards\\AddValueReward',
                            'attribute' => 'point',
                            'value' => '${point} + ${level}*3 + 3'
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
                        'class' => 'Liquid\\Processors\\PolicyProcessor',
                    ],
                    'policies' => [
                        'check_point_level_1' => [
                            'class' => "Liquid\\Processors\\Units\\Policies\\CheckValuePolicy",
                            'attribute' => 'point',
                            'condition' => 'intType|min:50',
                            'name' => 'check_point_level_1',
                        ]
                    ],
                    'rewards' => [
                        'reward_level_1' => [
                            'class' => 'Liquid\\Processors\\Units\\Rewards\\AddValueReward',
                            'attribute' => 'level',
                            'value' => 1
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
                        'class' => 'Liquid\\Processors\\PolicyProcessor',
                    ],
                    'policies' => [
                        'check_point_level_2' => [
                            'class' => "Liquid\\Processors\\Units\\Policies\\CheckValuePolicy",
                            'attribute' => 'point',
                            'condition' => 'intType|min:150',
                            'name' => 'check_point_level_2',
                        ]
                    ],
                    'rewards' => [
                        'reward_level_2' => [
                            'class' => 'Liquid\\Processors\\Units\\Rewards\\AddValueReward',
                            'attribute' => 'level',
                            'value' => 2
                        ]
                    ],
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ])->getBody())->data;

        $node_3 = json_decode($this->client->post("schema/{$schema->id}/nodes", [
            'json' => [
                'data' => [
                    'config' => [
                        'class' => 'Liquid\\Processors\\PolicyProcessor',
                    ],
                    'policies' => [
                        'check_point_level_3' => [
                            'class' => "Liquid\\Processors\\Units\\Policies\\CheckValuePolicy",
                            'attribute' => 'point',
                            'condition' => 'intType|min:300',
                            'name' => 'check_point_level_3',
                        ]
                    ],
                    'rewards' => [
                        'reward_level_3' => [
                            'class' => 'Liquid\\Processors\\Units\\Rewards\\AddValueReward',
                            'attribute' => 'level',
                            'value' => 3
                        ]
                    ],
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ])->getBody())->data;

        $node_4 = json_decode($this->client->post("schema/{$schema->id}/nodes", [
            'json' => [
                'data' => [
                    'config' => [
                        'class' => 'Liquid\\Processors\\PolicyProcessor',
                    ],
                    'policies' => [
                        'check_point_level_4' => [
                            'class' => "Liquid\\Processors\\Units\\Policies\\CheckValuePolicy",
                            'attribute' => 'point',
                            'condition' => 'intType|min:500',
                            'name' => 'check_point_level_4',
                        ]
                    ],
                    'rewards' => [
                        'reward_level_4' => [
                            'class' => 'Liquid\\Processors\\Units\\Rewards\\AddValueReward',
                            'attribute' => 'level',
                            'value' => 4
                        ]
                    ],
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ])->getBody())->data;

        $node_5 = json_decode($this->client->post("schema/{$schema->id}/nodes", [
            'json' => [
                'data' => [
                    'config' => [
                        'class' => 'Liquid\\Processors\\PolicyProcessor',
                    ],
                    'policies' => [
                        'check_point_level_5' => [
                            'class' => "Liquid\\Processors\\Units\\Policies\\CheckValuePolicy",
                            'attribute' => 'point',
                            'condition' => 'intType|min:750',
                            'name' => 'check_point_level_5',
                        ]
                    ],
                    'rewards' => [
                        'reward_level_5' => [
                            'class' => 'Liquid\\Processors\\Units\\Rewards\\AddValueReward',
                            'attribute' => 'level',
                            'value' => 5
                        ]
                    ],
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ])->getBody())->data;

        $this->client->post("schema/{$schema->id}/links", [
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

        $this->client->post("schema/{$schema->id}/links", [
            'json' => [
                'data' => [
                    'node_from' => $node_2->id,
                    'node_to' => $node_3->id,
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $this->client->post("schema/{$schema->id}/links", [
            'json' => [
                'data' => [
                    'node_from' => $node_3->id,
                    'node_to' => $node_4->id,
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        $this->client->post("schema/{$schema->id}/links", [
            'json' => [
                'data' => [
                    'node_from' => $node_4->id,
                    'node_to' => $node_5->id,
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]);

        return $schema;
    }

    protected function createSchemaUserComment($accessToken)
    {
        $schema = json_decode($this->client->post('schemas', [
            'json' => [
                'data' => [
                    'external_id' => 'schema.user.comment',
                    'name' => 'User comment property',
                    'description' => 'Schema for daily user comment',
                    'content' => [
                        'comment' => 1,
                    ],
                    'condition' => [
                        'category' => 'person',
                    ],
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
                        'check_comment_increment' => [
                            'class' => "Liquid\\Processors\\Units\\Policies\\CheckValueIncrement",
                            'attribute' => 'comment',
                            'increment' => 1,
                            'name' => 'check_comment_increment',
                        ]
                    ],
                    'rewards' => [
                        'r1' => [
                            'class' => 'Liquid\\Processors\\Units\\Rewards\\AddValueReward',
                            'attribute' => 'point',
                            'value' => '${point} + ${level} + 1'
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
                        'check_comment_increment' => [
                            'class' => "Liquid\\Processors\\Units\\Policies\\CheckConsecutiveRepeat",
                            'attribute' => 'comment',
                            'repeat' => 2,
                            'name' => 'check_comment_consecutive',
                        ]
                    ],
                    'rewards' => [
                        'r1' => [
                            'class' => 'Liquid\\Processors\\Units\\Rewards\\AddValueReward',
                            'attribute' => 'point',
                            'value' => '${point} + ${level} + 1'
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

    protected function createSchemaUserRating($accessToken)
    {
        $schema = json_decode($this->client->post('schemas', [
            'json' => [
                'data' => [
                    'external_id' => 'schema.user.rating',
                    'name' => 'User rating property',
                    'description' => 'Schema for daily user rating',
                    'content' => [
                        'rating' => 1,
                    ],
                    'condition' => [
                        'category' => 'person',
                    ],
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
                        'check_rating_increment' => [
                            'class' => "Liquid\\Processors\\Units\\Policies\\CheckValueIncrement",
                            'attribute' => 'rating',
                            'increment' => 1,
                            'name' => 'check_rating_increment',
                        ]
                    ],
                    'rewards' => [
                        'r1' => [
                            'class' => 'Liquid\\Processors\\Units\\Rewards\\AddValueReward',
                            'attribute' => 'point',
                            'value' => '${point} + ${level} + 1'
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
                        'check_rating_increment' => [
                            'class' => "Liquid\\Processors\\Units\\Policies\\CheckConsecutiveRepeat",
                            'attribute' => 'rating',
                            'repeat' => 2,
                            'name' => 'check_rating_consecutive',
                        ]
                    ],
                    'rewards' => [
                        'r1' => [
                            'class' => 'Liquid\\Processors\\Units\\Rewards\\AddValueReward',
                            'attribute' => 'point',
                            'value' => '${point} + ${level} + 1'
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

    protected function createEventUserInit($accessToken)
    {
        $event = json_decode($this->client->post('events', [
            'json' => [
                'data' => [
                    'external_id' => 'event.user.init',
                    'name' => 'User init event',
                    'description' => 'Event for user register to initialize basic info',
                    'content' => [
                        'login' => 0,
                        'posting' => 0,
                        'sharing' => 0,
                        'rating' => 0,
                        'comment' => 0,
                        'category' => 'person',
                    ],
                    'condition' => [],
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ])->getBody())->data;
    }

    protected function createEventUserLogin($accessToken, array $schemas)
    {
        $event = json_decode($this->client->post('events', [
            'json' => [
                'data' => [
                    'external_id' => 'event.user.login',
                    'name' => 'User login event',
                    'description' => 'Event for daily user login',
                    'content' => [
                        'login' => 1,
                    ],
                    'condition' => [
                        'category' => 'person',
                    ],
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ])->getBody())->data;

        $priority = 0;
        foreach ($schemas as $schema) {
            $this->client->post("event/{$event->id}/subscribers", [
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
                    'content' => [
                        'posting' => 1,
                    ],
                    'condition' => [
                        'category' => 'person',
                    ],
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ])->getBody())->data;

        $priority = 0;
        foreach ($schemas as $schema) {
            $this->client->post("event/{$event->id}/subscribers", [
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

    protected function createEventUserSharing($accessToken, array $schemas)
    {
        $event = json_decode($this->client->post('events', [
            'json' => [
                'data' => [
                    'external_id' => 'event.user.sharing',
                    'name' => 'User sharing event',
                    'description' => 'Event for daily user sharing',
                    'content' => [
                        'sharing' => 1,
                    ],
                    'condition' => [
                        'category' => 'person',
                    ],
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ])->getBody())->data;

        $priority = 0;
        foreach ($schemas as $schema) {
            $this->client->post("event/{$event->id}/subscribers", [
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

    protected function createEventUserRating($accessToken, array $schemas)
    {
        $event = json_decode($this->client->post('events', [
            'json' => [
                'data' => [
                    'external_id' => 'event.user.rating',
                    'name' => 'User rating event',
                    'description' => 'Event for daily user rating',
                    'content' => [
                        'rating' => 1,
                    ],
                    'condition' => [
                        'category' => 'person',
                    ],
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ])->getBody())->data;

        $priority = 0;
        foreach ($schemas as $schema) {
            $this->client->post("event/{$event->id}/subscribers", [
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

    protected function createEventUserComment($accessToken, array $schemas)
    {
        $event = json_decode($this->client->post('events', [
            'json' => [
                'data' => [
                    'external_id' => 'event.user.comment',
                    'name' => 'User comment event',
                    'description' => 'Event for daily user comment',
                    'content' => [
                        'comment' => 1,
                    ],
                    'condition' => [
                        'category' => 'person',
                    ],
                ],
            ],
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ])->getBody())->data;

        $priority = 0;
        foreach ($schemas as $schema) {
            $this->client->post("event/{$event->id}/subscribers", [
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
