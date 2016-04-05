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
            'id' => env('APP_ID'),
            'name' => 'come-stay.vn',
            'secret' => env('APP_SECRET'),
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d'),
        ]);

        \DB::table('oauth_scopes')->insert([
            'id' => 'read',
            'description' => 'this scope grants the permission to read on all endpoints',
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d'),
        ]);

        \DB::table('oauth_scopes')->insert([
            'id' => 'edit',
            'description' => 'this scope grants the permission to edit on all endpoints',
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d'),
        ]);

        \DB::table('oauth_scopes')->insert([
            'id' => 'execute',
            'description' => 'this scope grants the permission to execute on all endpoints',
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
            'id' => 'read.event',
            'description' => 'this scope grants the permission to read event',
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d'),
        ]);

        \DB::table('oauth_scopes')->insert([
            'id' => 'edit.event',
            'description' => 'this scope grants the permission to edit event',
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d'),
        ]);

        \DB::table('oauth_scopes')->insert([
            'id' => 'execute.event',
            'description' => 'this scope grants the permission to execute event',
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d'),
        ]);
    }
}
