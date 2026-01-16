<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // $user1 = User::factory()->create();
        // $user2 = User::factory()->create();

        // $notification = Notification::create([
        //     'user_id' => $user2->id,
        //     'from_user_id' => $user1->id,
        //     'message' => 'Hello from user 1',
        //     'type' => 'message',
        // ]);

        // $user2->notifications;
    }
}
