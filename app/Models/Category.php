<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'color',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function forumPosts()
    {
        return $this->hasMany(ForumPost::class);
    }

    // Helper methods
    public function postsCount()
    {
        return $this->posts()->count();
    }
}
