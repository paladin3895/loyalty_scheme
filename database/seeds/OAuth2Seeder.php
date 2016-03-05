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
            'id' => 'read.entity',
            'description' => 'this scope grants the permission to read entity',
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d'),
        ]);

        \DB::table('oauth_scopes')->insert([
            'id' => 'edit.entity',
            'description' => 'this scope grants the permission to edit entity',
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d'),
        ]);

        \DB::table('oauth_scopes')->insert([
            'id' => 'read.schema',
            'description' => 'this scope grants the permission to read schema',
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d'),
        ]);

        \DB::table('oauth_scopes')->insert([
            'id' => 'edit.schema',
            'description' => 'this scope grants the permission to edit schema',
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d'),
        ]);

        \DB::table('oauth_scopes')->insert([
            'id' => 'execute.schema',
            'description' => 'this scope grants the permission to execute schema',
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d'),
        ]);

        \DB::table('oauth_scopes')->insert([
            'id' => 'read.action',
            'description' => 'this scope grants the permission to read action',
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d'),
        ]);

        \DB::table('oauth_scopes')->insert([
            'id' => 'edit.action',
            'description' => 'this scope grants the permission to edit action',
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d'),
        ]);

        \DB::table('oauth_scopes')->insert([
            'id' => 'execute.action',
            'description' => 'this scope grants the permission to execute action',
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d'),
        ]);
    }
}
