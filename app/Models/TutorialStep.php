<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorialStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'target_element',
        'order',
        'is_active',
        'is_skippable',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_skippable' => 'boolean',
    ];

    // Relationships
    public function userProgress()
    {
        return $this->hasMany(UserTutorialProgress::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}