<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@eventtick.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Create regular user
        \App\Models\User::create([
            'name' => 'User',
            'email' => 'user@eventtick.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        // Create sample events
        \App\Models\Event::create([
            'title' => 'Tech Conference 2024',
            'description' => 'Konferensi teknologi terbesar di Indonesia dengan pembicara internasional.',
            'venue' => 'Jakarta Convention Center',
            'event_date' => now()->addMonths(2),
            'capacity' => 500,
            'price' => 250000,
            'status' => 'published'
        ]);

        \App\Models\Event::create([
            'title' => 'Music Festival Summer',
            'description' => 'Festival musik musim panas dengan berbagai genre musik.',
            'venue' => 'Ancol Beach',
            'event_date' => now()->addMonths(3),
            'capacity' => 1000,
            'price' => 150000,
            'status' => 'published'
        ]);

        \App\Models\Event::create([
            'title' => 'Business Networking Event',
            'description' => 'Event networking untuk para profesional dan pengusaha.',
            'venue' => 'Grand Hyatt Jakarta',
            'event_date' => now()->addMonths(1),
            'capacity' => 200,
            'price' => 500000,
            'status' => 'published'
        ]);

        // Seed bank accounts
        $this->call([
            BankAccountSeeder::class
        ]);
    }
}
