<?php

namespace App\Http\Controllers;

use App\Models\TutorialStep;
use App\Models\UserTutorialProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TutorialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tutorialSteps = TutorialStep::active()
            ->ordered()
            ->get();

        $completedSteps = Auth::user()->tutorialProgress()
            ->where('completed', true)
            ->pluck('tutorial_step_id')
            ->toArray();

        return view('tutorial.index', compact('tutorialSteps', 'completedSteps'));
    }

    public function complete(Request $request)
    {
        $stepId = $request->input('step_id');
        $tutorialStep = TutorialStep::findOrFail($stepId);

        UserTutorialProgress::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'tutorial_step_id' => $stepId,
            ],
            [
                'completed' => true,
                'skipped' => false,
            ]
        );

        return response()->json(['success' => true]);
    }

    public function skip(Request $request)
    {
        $stepId = $request->input('step_id');
        $tutorialStep = TutorialStep::findOrFail($stepId);

        if (!$tutorialStep->is_skippable) {
            return response()->json(['error' => 'This step cannot be skipped'], 400);
        }

        UserTutorialProgress::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'tutorial_step_id' => $stepId,
            ],
            [
                'completed' => false,
                'skipped' => true,
            ]
        );

        return response()->json(['success' => true]);
    }

    public function getProgress()
    {
        $totalSteps = TutorialStep::active()->count();
        $completedSteps = Auth::user()->tutorialProgress()
            ->where(function ($query) {
                $query->where('completed', true)
                      ->orWhere('skipped', true);
            })
            ->count();

        $progress = $totalSteps > 0 ? ($completedSteps / $totalSteps) * 100 : 0;

        return response()->json([
            'total_steps' => $totalSteps,
            'completed_steps' => $completedSteps,
            'progress' => round($progress, 2)
        ]);
    }
}
