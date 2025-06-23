<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PomodoroSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_name',
        'type',
        'duration_minutes',
        'started_at',
        'completed_at',
        'status',
        'notes'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function getRemainingTimeAttribute()
    {
        if ($this->status !== 'in_progress' || !$this->started_at) {
            return 0;
        }

        $elapsed = $this->started_at->diffInSeconds(now());
        $totalSeconds = $this->duration_minutes * 60;
        
        return max(0, $totalSeconds - $elapsed);
    }

    public function getElapsedTimeAttribute()
    {
        if (!$this->started_at) {
            return 0;
        }

        $endTime = $this->completed_at ?? now();
        return $this->started_at->diffInSeconds($endTime);
    }
}
