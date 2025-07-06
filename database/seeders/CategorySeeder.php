<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Technology',
                'description' => 'Programming, software development, and tech trends',
                'color' => '#3b82f6',
            ],
            [
                'name' => 'Science',
                'description' => 'Scientific discoveries, research, and experiments',
                'color' => '#10b981',
            ],
            [
                'name' => 'Mathematics',
                'description' => 'Math concepts, problem solving, and tutorials',
                'color' => '#f59e0b',
            ],
            [
                'name' => 'Language Learning',
                'description' => 'Foreign languages, grammar, and communication skills',
                'color' => '#ef4444',
            ],
            [
                'name' => 'Business',
                'description' => 'Entrepreneurship, marketing, and business strategies',
                'color' => '#8b5cf6',
            ],
            [
                'name' => 'Art & Design',
                'description' => 'Creative arts, design principles, and visual communication',
                'color' => '#ec4899',
            ],
            [
                'name' => 'Health & Wellness',
                'description' => 'Physical health, mental wellness, and lifestyle',
                'color' => '#06b6d4',
            ],
            [
                'name' => 'History',
                'description' => 'Historical events, cultures, and civilizations',
                'color' => '#84cc16',
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'description' => $category['description'],
                'slug' => Str::slug($category['name']),
                'color' => $category['color'],
                'is_active' => true,
            ]);
        }
    }
}
