<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            HelpTopicSeeder::class,
            TutorialStepSeeder::class,
        ]);

        // Create demo admin user
        \App\Models\User::factory()->create([
            'name' => 'Smartgram Admin',
            'email' => 'admin@smartgram.com',
            'username' => 'admin',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create demo users
        \App\Models\User::factory()->create([
            'name' => 'John Mentor',
            'email' => 'mentor@smartgram.com',
            'username' => 'johnmentor',
            'role' => 'mentor',
            'bio' => 'Passionate educator with 10+ years of experience in technology and programming.',
            'email_verified_at' => now(),
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Sarah Learner',
            'email' => 'learner@smartgram.com',
            'username' => 'sarahlearner',
            'role' => 'learner',
            'bio' => 'Computer science student eager to learn and share knowledge with the community.',
            'email_verified_at' => now(),
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Mike Creator',
            'email' => 'creator@smartgram.com',
            'username' => 'mikecreator',
            'role' => 'content_creator',
            'bio' => 'Content creator specializing in educational videos and tutorials.',
            'email_verified_at' => now(),
        ]);
    }
}
