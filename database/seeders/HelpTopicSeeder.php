<?php

namespace Database\Seeders;

use App\Models\HelpTopic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HelpTopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $helpTopics = [
            [
                'title' => 'Getting Started with Smartgram',
                'content' => 'Welcome to Smartgram! This guide will help you get started with our social learning platform. Learn how to create your profile, connect with other learners, and share knowledge effectively.',
                'order' => 1,
            ],
            [
                'title' => 'Creating and Managing Posts',
                'content' => 'Learn how to create engaging posts, add media content, organize your posts with categories, and manage your published content. Discover best practices for creating educational content that resonates with your audience.',
                'order' => 2,
            ],
            [
                'title' => 'Connecting with Other Users',
                'content' => 'Discover how to follow other users, build your learning network, engage with content through likes and comments, and participate in meaningful discussions within the community.',
                'order' => 3,
            ],
            [
                'title' => 'Using the Forum Feature',
                'content' => 'The forum is a great place for discussions and Q&A. Learn how to create forum posts, participate in discussions, and find answers to your questions from the community.',
                'order' => 4,
            ],
            [
                'title' => 'Search and Discovery',
                'content' => 'Find the content and users you\'re looking for using our powerful search features. Learn about filtering by categories, finding specific topics, and discovering new learning opportunities.',
                'order' => 5,
            ],
            [
                'title' => 'Notifications and Settings',
                'content' => 'Stay updated with notifications about likes, comments, follows, and new content from users you follow. Learn how to manage your notification preferences and account settings.',
                'order' => 6,
            ],
            [
                'title' => 'Profile Customization',
                'content' => 'Make your profile stand out by adding a bio, profile picture, and showcasing your expertise. Learn how to present yourself as a learner, mentor, or content creator.',
                'order' => 7,
            ],
            [
                'title' => 'Community Guidelines',
                'content' => 'Our community guidelines ensure a positive learning environment for everyone. Learn about our policies on content sharing, respectful communication, and academic integrity.',
                'order' => 8,
            ],
        ];

        foreach ($helpTopics as $topic) {
            HelpTopic::create([
                'title' => $topic['title'],
                'content' => $topic['content'],
                'slug' => Str::slug($topic['title']),
                'order' => $topic['order'],
                'is_published' => true,
            ]);
        }
    }
}
