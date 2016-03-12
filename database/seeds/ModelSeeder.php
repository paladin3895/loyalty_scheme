<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Entity;
use App\Models\Schema;
use App\Models\Event;

class ModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = \DB::table('oauth_clients')->first();
        factory(Entity::class, 'person', 5)->create([
            'client_id' => $client->id,
        ]);

        factory(Entity::class, 'property', 5)->create([
            'client_id' => $client->id,
        ]);

        factory(Schema::class, 5)->create([
            'client_id' => $client->id,
        ]);

        factory(Event::class, 5)->create([
            'client_id' => $client->id,
        ]);
    }
}
