<?php

namespace Database\Seeders;

use App\Models\TutorialStep;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TutorialStepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tutorialSteps = [
            [
                'title' => 'Welcome to Smartgram!',
                'content' => 'Welcome to Smartgram, your social learning platform! This tutorial will guide you through the main features to help you get started.',
                'target_element' => null,
                'order' => 1,
                'is_skippable' => false,
            ],
            [
                'title' => 'Your Dashboard',
                'content' => 'This is your dashboard where you\'ll see posts from users you follow. You can also see your profile stats in the sidebar.',
                'target_element' => '.sidebar',
                'order' => 2,
                'is_skippable' => true,
            ],
            [
                'title' => 'Create Your First Post',
                'content' => 'Click here to create your first post! Share your knowledge, ask questions, or start a discussion.',
                'target_element' => 'a[href*="posts/create"]',
                'order' => 3,
                'is_skippable' => true,
            ],
            [
                'title' => 'Search and Discover',
                'content' => 'Use the search bar to find content, users, and topics that interest you. You can filter by categories too!',
                'target_element' => 'form[action*="search"]',
                'order' => 4,
                'is_skippable' => true,
            ],
            [
                'title' => 'Notifications',
                'content' => 'Stay updated with notifications about likes, comments, and new followers. Click the bell icon to see your notifications.',
                'target_element' => '.fa-bell',
                'order' => 5,
                'is_skippable' => true,
            ],
            [
                'title' => 'Forum Discussions',
                'content' => 'Join discussions in our forum section. Ask questions, share insights, and learn from the community.',
                'target_element' => 'a[href*="forum"]',
                'order' => 6,
                'is_skippable' => true,
            ],
            [
                'title' => 'Get Help',
                'content' => 'If you need help using Smartgram, check out our help section with detailed guides and FAQs.',
                'target_element' => 'a[href*="help"]',
                'order' => 7,
                'is_skippable' => true,
            ],
            [
                'title' => 'Complete Your Profile',
                'content' => 'Don\'t forget to complete your profile! Add a bio, profile picture, and showcase your expertise.',
                'target_element' => '.dropdown-toggle',
                'order' => 8,
                'is_skippable' => true,
            ],
        ];

        foreach ($tutorialSteps as $step) {
            TutorialStep::create($step);
        }
    }
}
