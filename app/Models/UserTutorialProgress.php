<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTutorialProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tutorial_step_id',
        'completed',
        'skipped',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'skipped' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tutorialStep()
    {
        return $this->belongsTo(TutorialStep::class);
    }
}