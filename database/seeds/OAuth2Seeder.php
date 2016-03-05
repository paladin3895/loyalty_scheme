<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class OAuth2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('oauth_clients')->insert([
            'id' => 'policy_testing_account',
            'name' => 'come-stay.vn',
            'secret' => 'policy_testing_secret',
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d'),
        ]);

        \DB::table('oauth_scopes')->insert([
            'id' => 'read',
            'description' => 'this scope grants the read permission',
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d'),
        ]);

        \DB::table('oauth_scopes')->insert([
            'id' => 'write',
            'description' => 'this scope grants the edit permission',
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d'),
        ]);

        \DB::table('oauth_scopes')->insert([
            'id' => 'execute',
            'description' => 'this scope grants the execute permission',
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d'),
        ]);
    }
}
