<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'content',
        'is_sticky',
        'is_closed',
        'views_count',
    ];

    protected $casts = [
        'is_sticky' => 'boolean',
        'is_closed' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    // Helper methods
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function repliesCount()
    {
        return $this->comments()->count();
    }

    public function lastReply()
    {
        return $this->comments()->latest()->first();
    }
}