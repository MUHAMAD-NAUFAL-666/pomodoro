<?php

namespace App\Http\Controllers;

use App\Models\PomodoroSession;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PomodoroController extends Controller
{
    public function index()
    {
        $currentSession = PomodoroSession::where('status', 'in_progress')->first();
        $recentSessions = PomodoroSession::orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        $todayStats = [
            'completed_work' => PomodoroSession::whereDate('created_at', today())
                ->where('type', 'work')
                ->where('status', 'completed')
                ->count(),
            'total_time' => PomodoroSession::whereDate('created_at', today())
                ->where('status', 'completed')
                ->sum('duration_minutes')
        ];

        return view('pomodoro.index', compact('currentSession', 'recentSessions', 'todayStats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'task_name' => 'required|string|max:255',
            'type' => 'required|in:work,short_break,long_break',
            'duration_minutes' => 'required|integer|min:1|max:120'
        ]);

        // Cancel any existing in-progress session
        PomodoroSession::where('status', 'in_progress')
            ->update(['status' => 'cancelled']);

        $session = PomodoroSession::create($request->all());

        return response()->json([
            'success' => true,
            'session' => $session
        ]);
    }

    public function start($id)
    {
        $session = PomodoroSession::findOrFail($id);
        
        // Cancel any other in-progress sessions
        PomodoroSession::where('status', 'in_progress')
            ->where('id', '!=', $id)
            ->update(['status' => 'cancelled']);

        $session->update([
            'status' => 'in_progress',
            'started_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'session' => $session->fresh()
        ]);
    }

    public function complete($id)
    {
        $session = PomodoroSession::findOrFail($id);
        
        $session->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'session' => $session->fresh()
        ]);
    }

    public function cancel($id)
    {
        $session = PomodoroSession::findOrFail($id);
        
        $session->update([
            'status' => 'cancelled'
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function getCurrentSession()
    {
        $session = PomodoroSession::where('status', 'in_progress')->first();
        
        return response()->json([
            'session' => $session,
            'remaining_time' => $session ? $session->remaining_time : 0
        ]);
    }
}