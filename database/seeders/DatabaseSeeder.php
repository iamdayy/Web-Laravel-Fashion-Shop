<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        \App\Models\User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'username' => 'admin',
            'photo' => '/storage/images/person-dummy.jpg',
            'address' => 'Admin Address',
            'password' => bcrypt('admin'), // password
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        \App\Models\User::factory(10)->create();
        $this->call([ItemSeeder::class, BannerSeeder::class, OfferSeeder::class]);
    }
}
