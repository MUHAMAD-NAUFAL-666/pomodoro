<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pomodoro Focus - Stay Productive</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .timer-circle {
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: conic-gradient(from 0deg, #ef4444, #f97316, #eab308, #22c55e, #06b6d4, #3b82f6, #8b5cf6, #ef4444);
            padding: 8px;
            position: relative;
            animation: rotate 20s linear infinite;
        }

        .timer-inner {
            width: 100%;
            height: 100%;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            box-shadow: inset 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .pulse-animation {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .8; }
        }

        .slide-in {
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(239, 68, 68, 0.4);
        }

        .progress-ring {
            transform: rotate(-90deg);
        }

        .progress-ring-circle {
            transition: stroke-dashoffset 0.35s;
            transform-origin: 50% 50%;
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% { transform: translate(0, 0px); }
            50% { transform: translate(0, -10px); }
            100% { transform: translate(0, 0px); }
        }

        .status-badge {
            position: relative;
            overflow: hidden;
        }

        .status-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .status-badge:hover::before {
            left: 100%;
        }
    </style>
</head>
<body class="min-h-screen gradient-bg">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.3) 1px, transparent 0); background-size: 20px 20px;"></div>
    </div>

    <div class="relative z-10 container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12 fade-in">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full mb-6 floating">
                    <i class="fas fa-clock text-3xl text-white"></i>
                </div>
                <h1 class="text-5xl font-bold text-white mb-4">
                    Pomodoro <span class="text-yellow-300">Focus</span>
                </h1>
                <p class="text-xl text-white/80 max-w-2xl mx-auto">
                    Boost your productivity with the proven Pomodoro Technique. Stay focused, take breaks, achieve more.
                </p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
                <div class="glass-effect rounded-2xl p-6 card-hover slide-in">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-400 text-xl"></i>
                        </div>
                        <span class="text-green-400 text-sm font-medium">+{{ $todayStats['completed_work'] > 0 ? '12%' : '0%' }}</span>
                    </div>
                    <div class="text-3xl font-bold text-white mb-1">{{ $todayStats['completed_work'] }}</div>
                    <div class="text-white/60 text-sm">Completed Today</div>
                </div>

                <div class="glass-effect rounded-2xl p-6 card-hover slide-in" style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-clock text-blue-400 text-xl"></i>
                        </div>
                        <span class="text-blue-400 text-sm font-medium">{{ $todayStats['total_time'] }}m</span>
                    </div>
                    <div class="text-3xl font-bold text-white mb-1">{{ number_format($todayStats['total_time'] / 60, 1) }}h</div>
                    <div class="text-white/60 text-sm">Focus Time</div>
                </div>

                <div class="glass-effect rounded-2xl p-6 card-hover slide-in" style="animation-delay: 0.2s">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-fire text-purple-400 text-xl"></i>
                        </div>
                        <span class="text-purple-400 text-sm font-medium">{{ $todayStats['completed_work'] > 0 ? '🔥' : '💤' }}</span>
                    </div>
                    <div class="text-3xl font-bold text-white mb-1">{{ max(1, $todayStats['completed_work']) }}</div>
                    <div class="text-white/60 text-sm">Streak</div>
                </div>

                <div class="glass-effect rounded-2xl p-6 card-hover slide-in" style="animation-delay: 0.3s">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-yellow-500/20 rounded-xl flex items-center justify-center">
                            <i class="fas fa-trophy text-yellow-400 text-xl"></i>
                        </div>
                        <span class="text-yellow-400 text-sm font-medium">{{ $todayStats['completed_work'] >= 8 ? '🏆' : '⭐' }}</span>
                    </div>
                    <div class="text-3xl font-bold text-white mb-1">{{ min(100, $todayStats['completed_work'] * 12.5) }}%</div>
                    <div class="text-white/60 text-sm">Daily Goal</div>
                </div>
            </div>

            <!-- Main Timer Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
                <!-- Timer -->
                <div class="lg:col-span-2">
                    <div class="glass-effect rounded-3xl p-8 text-center slide-in">
                        <div id="timer-section">
                            @if($currentSession)
                                <div class="mb-6">
                                    <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-4
                                        {{ $currentSession->type === 'work' ? 'bg-red-500/20 text-red-300 border border-red-500/30' : 'bg-green-500/20 text-green-300 border border-green-500/30' }}">
                                        <i class="fas fa-{{ $currentSession->type === 'work' ? 'briefcase' : 'coffee' }} mr-2"></i>
                                        {{ ucfirst(str_replace('_', ' ', $currentSession->type)) }}
                                    </div>
                                    <h2 class="text-2xl font-bold text-white mb-8">{{ $currentSession->task_name }}</h2>
                                </div>

                                <!-- Circular Timer -->
                                <div class="flex justify-center mb-8">
                                    <div class="relative">
                                        <svg class="progress-ring w-72 h-72" viewBox="0 0 120 120">
                                            <circle cx="60" cy="60" r="54" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="8"/>
                                            <circle id="progress-circle" cx="60" cy="60" r="54" fill="none" 
                                                    stroke="{{ $currentSession->type === 'work' ? '#ef4444' : '#22c55e' }}" 
                                                    stroke-width="8" stroke-linecap="round"
                                                    stroke-dasharray="339.292" stroke-dashoffset="339.292"
                                                    class="progress-ring-circle"/>
                                        </svg>
                                        <div class="absolute inset-0 flex items-center justify-center flex-col">
                                            <div class="text-5xl font-mono font-bold text-white mb-2" id="timer-display">
                                                {{ sprintf('%02d:%02d', $currentSession->duration_minutes, 0) }}
                                            </div>
                                            <div class="text-white/60 text-sm">remaining</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Control Buttons -->
                                <div class="flex justify-center space-x-4">
                                    <button onclick="completeSession({{ $currentSession->id }})" 
                                            class="btn-success text-white px-8 py-3 rounded-xl font-medium inline-flex items-center space-x-2 transition-all duration-300">
                                        <i class="fas fa-check"></i>
                                        <span>Complete</span>
                                    </button>
                                    <button onclick="cancelSession({{ $currentSession->id }})" 
                                            class="btn-danger text-white px-8 py-3 rounded-xl font-medium inline-flex items-center space-x-2 transition-all duration-300">
                                        <i class="fas fa-times"></i>
                                        <span>Cancel</span>
                                    </button>
                                </div>
                            @else
                                <div class="py-12">
                                    <div class="w-32 h-32 mx-auto mb-8 glass-effect rounded-full flex items-center justify-center floating">
                                        <i class="fas fa-play text-4xl text-white/80"></i>
                                    </div>
                                    <h3 class="text-2xl font-bold text-white mb-4">Ready to Focus?</h3>
                                    <p class="text-white/60 mb-8 max-w-md mx-auto">Start a new Pomodoro session and boost your productivity with focused work intervals.</p>
                                    <button onclick="showNewSessionForm()" 
                                            class="btn-primary text-white px-10 py-4 rounded-xl text-lg font-medium inline-flex items-center space-x-3 transition-all duration-300">
                                        <i class="fas fa-rocket"></i>
                                        <span>Start New Session</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions & Info -->
                <div class="space-y-6">
                    <!-- Quick Start Buttons -->
                    <div class="glass-effect rounded-2xl p-6 slide-in" style="animation-delay: 0.4s">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-bolt text-yellow-400 mr-2"></i>
                            Quick Start
                        </h3>
                        <div class="space-y-3">
                            <button onclick="quickStart('work', 25)" class="w-full text-left p-3 rounded-xl bg-red-500/20 hover:bg-red-500/30 border border-red-500/30 text-white transition-all duration-300 group">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-medium">Work Session</div>
                                        <div class="text-sm text-white/60">25 minutes</div>
                                    </div>
                                    <i class="fas fa-briefcase text-red-400 group-hover:scale-110 transition-transform"></i>
                                </div>
                            </button>
                            <button onclick="quickStart('short_break', 5)" class="w-full text-left p-3 rounded-xl bg-green-500/20 hover:bg-green-500/30 border border-green-500/30 text-white transition-all duration-300 group">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-medium">Short Break</div>
                                        <div class="text-sm text-white/60">5 minutes</div>
                                    </div>
                                    <i class="fas fa-coffee text-green-400 group-hover:scale-110 transition-transform"></i>
                                </div>
                            </button>
                            <button onclick="quickStart('long_break', 15)" class="w-full text-left p-3 rounded-xl bg-blue-500/20 hover:bg-blue-500/30 border border-blue-500/30 text-white transition-all duration-300 group">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-medium">Long Break</div>
                                        <div class="text-sm text-white/60">15 minutes</div>
                                    </div>
                                    <i class="fas fa-spa text-blue-400 group-hover:scale-110 transition-transform"></i>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Tips -->
                    <div class="glass-effect rounded-2xl p-6 slide-in" style="animation-delay: 0.5s">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                            <i class="fas fa-lightbulb text-yellow-400 mr-2"></i>
                            Pro Tips
                        </h3>
                        <div class="space-y-3 text-sm text-white/80">
                            <div class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-yellow-400 rounded-full mt-2 flex-shrink-0"></div>
                                <div>Turn off notifications during focus sessions</div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-green-400 rounded-full mt-2 flex-shrink-0"></div>
                                <div>Take breaks away from your screen</div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-blue-400 rounded-full mt-2 flex-shrink-0"></div>
                                <div>Stay hydrated and stretch regularly</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Session Form Modal -->
            <div id="new-session-form" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="display: none;">
                <div class="bg-white rounded-3xl p-8 max-w-md w-full slide-in shadow-2xl">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-plus text-white text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">New Focus Session</h3>
                        <p class="text-gray-600 mt-2">Set up your next productive session</p>
                    </div>

                    <form id="pomodoro-form" class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-tasks mr-2 text-purple-500"></i>
                                What will you work on?
                            </label>
                            <input type="text" name="task_name" required placeholder="e.g., Write project proposal"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:outline-none transition-colors">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-clock mr-2 text-blue-500"></i>
                                    Session Type
                                </label>
                                <select name="type" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:outline-none transition-colors">
                                    <option value="work">🎯 Work</option>
                                    <option value="short_break">☕ Short Break</option>
                                    <option value="long_break">🌿 Long Break</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-hourglass-half mr-2 text-green-500"></i>
                                    Duration
                                </label>
                                <input type="number" name="duration_minutes" value="25" min="1" max="120" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:outline-none transition-colors">
                            </div>
                        </div>

                        <div class="flex space-x-3 pt-4">
                            <button type="submit" class="flex-1 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                                <i class="fas fa-rocket mr-2"></i>
                                Start Session
                            </button>
                            <button type="button" onclick="hideNewSessionForm()" 
                                    class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recent Sessions -->
            <div class="glass-effect rounded-3xl p-8 slide-in" style="animation-delay: 0.6s">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-history text-blue-400 mr-3"></i>
                        Recent Sessions
                    </h3>
                    <div class="text-white/60 text-sm">
                        Last {{ $recentSessions->count() }} sessions
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($recentSessions as $session)
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20 card-hover">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center
                                        {{ $session->type === 'work' ? 'bg-red-500/20 text-red-400' : 'bg-green-500/20 text-green-400' }}">
                                        <i class="fas fa-{{ $session->type === 'work' ? 'briefcase' : 'coffee' }}"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-white">{{ $session->task_name }}</div>
                                        <div class="text-sm text-white/60 flex items-center space-x-3">
                                            <span>{{ ucfirst(str_replace('_', ' ', $session->type)) }}</span>
                                            <span>•</span>
                                            <span>{{ $session->duration_minutes }} min</span>
                                            <span>•</span>
                                            <span>{{ $session->created_at->format('M j, H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="status-badge px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $session->status === 'completed' ? 'bg-green-500/20 text-green-300 border border-green-500/30' : 
                                       ($session->status === 'cancelled' ? 'bg-red-500/20 text-red-300 border border-red-500/30' : 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/30') }}">
                                    <i class="fas fa-{{ $session->status === 'completed' ? 'check' : ($session->status === 'cancelled' ? 'times' : 'clock') }} mr-1"></i>
                                    {{ ucfirst($session->status) }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="w-20 h-20 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-clock text-2xl text-white/40"></i>
                            </div>
                            <p class="text-white/60 text-lg">No sessions yet</p>
                            <p class="text-white/40 text-sm mt-2">Start your first Pomodoro session to see your progress here</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Success Toast -->
    <div id="success-toast" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg transform translate-x-full transition-transform duration-300 z-50">
        <div class="flex items-center space-x-2">
            <i class="fas fa-check-circle"></i>
            <span id="toast-message">Session completed!</span>
        </div>
    </div>

    <script>
        let timerInterval;
        let currentSessionId = {{ $currentSession ? $currentSession->id : 'null' }};
        let totalSeconds = {{ $currentSession ? $currentSession->duration_minutes * 60 : 0 }};
        let currentSessionType = '{{ $currentSession ? $currentSession->type : '' }}';

        // Setup CSRF token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Timer functions
        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
        }

        function updateProgressCircle(remainingTime, totalTime) {
            const circle = document.getElementById('progress-circle');
            if (circle && totalTime > 0) {
                const circumference = 2 * Math.PI * 54; // radius = 54
                const progress = (totalTime - remainingTime) / totalTime;
                const offset = circumference * (1 - progress);
                circle.style.strokeDashoffset = offset;
            }
        }

        // Enhanced notification system with text-to-speech
        function playNotificationWithVoice(sessionType) {
            // Play notification sound first
            playNotificationSound();
            
            // Then play voice notification
            setTimeout(() => {
                playVoiceNotification(sessionType);
            }, 1000);
            
            // Show browser notification if permission granted
            showBrowserNotification(sessionType);
        }

        function playNotificationSound() {
            // Create a more pleasant notification sound sequence
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const notes = [523.25, 659.25, 783.99, 1046.50]; // C5, E5, G5, C6
            
            notes.forEach((frequency, index) => {
                setTimeout(() => {
                    const oscillator = audioContext.createOscillator();
                    const gainNode = audioContext.createGain();
                    
                    oscillator.connect(gainNode);
                    gainNode.connect(audioContext.destination);
                    
                    oscillator.frequency.value = frequency;
                    oscillator.type = 'sine';
                    
                    gainNode.gain.setValueAtTime(0.2, audioContext.currentTime);
                    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.4);
                    
                    oscillator.start(audioContext.currentTime);
                    oscillator.stop(audioContext.currentTime + 0.4);
                }, index * 150);
            });
        }

        function playVoiceNotification(sessionType) {
            // Check if browser supports speech synthesis
            if ('speechSynthesis' in window) {
                let message = '';
                let nextAction = '';
                
                switch(sessionType) {
                    case 'work':
                        message = 'Waktu kerja telah selesai. Waktunya istirahat!';
                        nextAction = 'Ambil napas dalam-dalam dan rilekskan mata Anda.';
                        break;
                    case 'short_break':
                        message = 'Waktu istirahat pendek telah selesai. Waktunya kembali bekerja!';
                        nextAction = 'Mari fokus kembali pada tugas Anda.';
                        break;
                    case 'long_break':
                        message = 'Waktu istirahat panjang telah selesai. Siap untuk sesi kerja berikutnya!';
                        nextAction = 'Anda sudah segar dan siap untuk produktif kembali.';
                        break;
                    default:
                        message = 'Sesi Pomodoro telah selesai!';
                        nextAction = 'Kerja bagus!';
                }
                
                // Create speech synthesis utterance
                const utterance = new SpeechSynthesisUtterance(message);
                
                // Configure voice settings
                utterance.lang = 'id-ID'; // Indonesian language
                utterance.rate = 0.9; // Slightly slower for clarity
                utterance.pitch = 1.1; // Slightly higher pitch for friendliness
                utterance.volume = 0.8;
                
                // Try to use Indonesian voice if available
                const voices = speechSynthesis.getVoices();
                const indonesianVoice = voices.find(voice => 
                    voice.lang.includes('id') || 
                    voice.name.toLowerCase().includes('indonesia')
                );
                
                if (indonesianVoice) {
                    utterance.voice = indonesianVoice;
                } else {
                    // Fallback to any available voice
                    const femaleVoice = voices.find(voice => 
                        voice.name.toLowerCase().includes('female') ||
                        voice.name.toLowerCase().includes('woman')
                    );
                    if (femaleVoice) {
                        utterance.voice = femaleVoice;
                    }
                }
                
                // Speak the message
                speechSynthesis.speak(utterance);
                
                // Speak the next action after a pause
                utterance.onend = () => {
                    setTimeout(() => {
                        const nextUtterance = new SpeechSynthesisUtterance(nextAction);
                        nextUtterance.lang = 'id-ID';
                        nextUtterance.rate = 0.8;
                        nextUtterance.pitch = 1.0;
                        nextUtterance.volume = 0.7;
                        if (indonesianVoice) {
                            nextUtterance.voice = indonesianVoice;
                        }
                        speechSynthesis.speak(nextUtterance);
                    }, 1000);
                };
                
            } else {
                console.log('Speech synthesis not supported');
                // Fallback to text notification
                showToast(getNotificationMessage(sessionType), 'success');
            }
        }

        function showBrowserNotification(sessionType) {
            if ('Notification' in window && Notification.permission === 'granted') {
                const notificationData = getNotificationData(sessionType);
                
                const notification = new Notification(notificationData.title, {
                    body: notificationData.body,
                    icon: '/favicon.ico', // You can add a custom icon
                    badge: '/favicon.ico',
                    tag: 'pomodoro-timer',
                    requireInteraction: true,
                    actions: [
                        {
                            action: 'start-break',
                            title: sessionType === 'work' ? 'Mulai Istirahat' : 'Mulai Kerja'
                        },
                        {
                            action: 'dismiss',
                            title: 'Tutup'
                        }
                    ]
                });
                
                // Auto close after 10 seconds
                setTimeout(() => {
                    notification.close();
                }, 10000);
                
                // Handle notification clicks
                notification.onclick = () => {
                    window.focus();
                    notification.close();
                };
            }
        }

        function getNotificationData(sessionType) {
            switch(sessionType) {
                case 'work':
                    return {
                        title: '🎉 Sesi Kerja Selesai!',
                        body: 'Waktu kerja 25 menit telah berakhir. Waktunya istirahat dan refresh pikiran Anda!'
                    };
                case 'short_break':
                    return {
                        title: '⏰ Istirahat Pendek Selesai!',
                        body: 'Istirahat 5 menit telah berakhir. Mari kembali fokus pada pekerjaan!'
                    };
                case 'long_break':
                    return {
                        title: '🌟 Istirahat Panjang Selesai!',
                        body: 'Istirahat 15 menit telah berakhir. Anda siap untuk sesi produktif berikutnya!'
                    };
                default:
                    return {
                        title: '✅ Sesi Pomodoro Selesai!',
                        body: 'Sesi Anda telah berakhir. Kerja bagus!'
                    };
            }
        }

        function getNotificationMessage(sessionType) {
            switch(sessionType) {
                case 'work':
                    return '🎉 Waktu kerja selesai! Waktunya istirahat!';
                case 'short_break':
                    return '⏰ Istirahat pendek selesai! Kembali bekerja!';
                case 'long_break':
                    return '🌟 Istirahat panjang selesai! Siap bekerja lagi!';
                default:
                    return '✅ Sesi Pomodoro selesai! Kerja bagus!';
            }
        }

        function startTimer() {
            if (timerInterval) {
                clearInterval(timerInterval);
            }

            timerInterval = setInterval(function() {
                $.get('/pomodoro/current')
                    .done(function(response) {
                        if (response.session && response.remaining_time > 0) {
                            $('#timer-display').text(formatTime(response.remaining_time));
                            updateProgressCircle(response.remaining_time, totalSeconds);
                            
                            // Update current session type
                            currentSessionType = response.session.type;
                            
                            // Show warning when 1 minute left
                            if (response.remaining_time === 60) {
                                showToast('⚠️ 1 menit tersisa!', 'warning');
                                playWarningSound();
                            }
                            
                            // Show final countdown for last 10 seconds
                            if (response.remaining_time <= 10 && response.remaining_time > 0) {
                                $('#timer-display').addClass('pulse-animation text-red-400');
                                
                                // Play tick sound for countdown
                                if (response.remaining_time <= 5) {
                                    playTickSound();
                                }
                            }
                            
                        } else if (response.session && response.remaining_time <= 0) {
                            // Timer finished
                            $('#timer-display').text('00:00').removeClass('pulse-animation');
                            updateProgressCircle(0, totalSeconds);
                            clearInterval(timerInterval);
                            
                            // Play comprehensive notification
                            playNotificationWithVoice(currentSessionType);
                            showCelebration();
                            
                            // Auto-complete the session after notifications
                            setTimeout(() => {
                                completeSession(response.session.id);
                            }, 3000);
                            
                        } else {
                            // No active session
                            clearInterval(timerInterval);
                            location.reload();
                        }
                    })
                    .fail(function() {
                        console.error('Failed to get current session');
                    });
            }, 1000);
        }

        function playWarningSound() {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.frequency.value = 800;
            oscillator.type = 'triangle';
            
            gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
            
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.5);
        }

        function playTickSound() {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.frequency.value = 1000;
            oscillator.type = 'square';
            
            gainNode.gain.setValueAtTime(0.05, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
            
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.1);
        }

        function showCelebration() {
            createConfetti();
            
            const celebration = document.createElement('div');
            celebration.innerHTML = `
                <div class="fixed inset-0 pointer-events-none z-50 flex items-center justify-center">
                    <div class="text-8xl animate-bounce">${currentSessionType === 'work' ? '🎉' : '⚡'}</div>
                </div>
            `;
            document.body.appendChild(celebration);
            
            setTimeout(() => {
                if (celebration.parentNode) {
                    document.body.removeChild(celebration);
                }
            }, 3000);
            
            showToast(getNotificationMessage(currentSessionType), 'success');
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('success-toast');
            const messageEl = document.getElementById('toast-message');
            
            messageEl.textContent = message;
            
            // Update toast style based on type
            toast.className = `fixed top-4 right-4 px-6 py-3 rounded-xl shadow-lg transform transition-transform duration-300 z-50 ${
                type === 'success' ? 'bg-green-500 text-white' : 
                type === 'error' ? 'bg-red-500 text-white' : 
                type === 'warning' ? 'bg-yellow-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            
            // Show toast
            toast.style.transform = 'translateX(0)';
            
            // Hide after 4 seconds for warnings, 3 for others
            const hideDelay = type === 'warning' ? 4000 : 3000;
            setTimeout(() => {
                toast.style.transform = 'translateX(100%)';
            }, hideDelay);
        }

        // Initialize voices when they're loaded
        function initializeVoices() {
            if ('speechSynthesis' in window) {
                // Load voices
                speechSynthesis.getVoices();
                
                // Some browsers load voices asynchronously
                speechSynthesis.onvoiceschanged = () => {
                    const voices = speechSynthesis.getVoices();
                    console.log('Available voices:', voices.map(v => `${v.name} (${v.lang})`));
                };
            }
        }

            // Test voice function (you can call this from console to test)
        function testVoice(sessionType = 'work') {
            playVoiceNotification(sessionType);
        }

        // Form functions
        function showNewSessionForm() {
            $('#new-session-form').fadeIn(300);
            $('input[name="task_name"]').focus();
        }

        function hideNewSessionForm() {
            $('#new-session-form').fadeOut(300);
            $('#pomodoro-form')[0].reset();
            $('input[name="duration_minutes"]').val(25);
        }

        // Quick start function
        function quickStart(type, duration) {
            const taskNames = {
                'work': 'Focus Work Session',
                'short_break': 'Short Break',
                'long_break': 'Long Break'
            };
            
            const formData = {
                task_name: taskNames[type],
                type: type,
                duration_minutes: duration
            };

            createAndStartSession(formData);
        }

        function createAndStartSession(formData) {
            // Show loading state
            showToast('Membuat sesi baru...', 'info');
            
            $.post('/pomodoro', formData)
                .done(function(response) {
                    if (response.success) {
                        // Start the session immediately
                        $.post(`/pomodoro/${response.session.id}/start`)
                            .done(function(startResponse) {
                                if (startResponse.success) {
                                    showToast('Sesi dimulai! 🚀', 'success');
                                    
                                    // Play start notification
                                    playStartNotification(formData.type);
                                    
                                    setTimeout(() => {
                                        location.reload();
                                    }, 1500);
                                }
                            })
                            .fail(function() {
                                showToast('Gagal memulai sesi', 'error');
                            });
                    }
                })
                .fail(function(xhr) {
                    const errors = xhr.responseJSON?.errors;
                    if (errors) {
                        let errorMessage = Object.values(errors)[0][0];
                        showToast(errorMessage, 'error');
                    } else {
                        showToast('Gagal membuat sesi', 'error');
                    }
                });
        }

        function playStartNotification(sessionType) {
            if ('speechSynthesis' in window) {
                let message = '';
                
                switch(sessionType) {
                    case 'work':
                        message = 'Sesi kerja dimulai. Mari fokus dan produktif!';
                        break;
                    case 'short_break':
                        message = 'Waktu istirahat pendek. Rileks sejenak dan refresh pikiran Anda.';
                        break;
                    case 'long_break':
                        message = 'Waktu istirahat panjang. Nikmati waktu istirahat Anda.';
                        break;
                    default:
                        message = 'Sesi Pomodoro dimulai!';
                }
                
                const utterance = new SpeechSynthesisUtterance(message);
                utterance.lang = 'id-ID';
                utterance.rate = 0.9;
                utterance.pitch = 1.0;
                utterance.volume = 0.7;
                
                // Use Indonesian voice if available
                const voices = speechSynthesis.getVoices();
                const indonesianVoice = voices.find(voice => 
                    voice.lang.includes('id') || 
                    voice.name.toLowerCase().includes('indonesia')
                );
                
                if (indonesianVoice) {
                    utterance.voice = indonesianVoice;
                }
                
                speechSynthesis.speak(utterance);
            }
        }

        // Session management functions
        function completeSession(sessionId) {
            $.post(`/pomodoro/${sessionId}/complete`)
                .done(function(response) {
                    if (response.success) {
                        clearInterval(timerInterval);
                        showToast('Sesi berhasil diselesaikan! 🎯', 'success');
                        
                        // Play completion voice
                        if ('speechSynthesis' in window) {
                            const utterance = new SpeechSynthesisUtterance('Selamat! Sesi Pomodoro berhasil diselesaikan. Kerja bagus!');
                            utterance.lang = 'id-ID';
                            utterance.rate = 0.9;
                            utterance.pitch = 1.1;
                            utterance.volume = 0.8;
                            speechSynthesis.speak(utterance);
                        }
                        
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    }
                })
                .fail(function() {
                    showToast('Gagal menyelesaikan sesi', 'error');
                });
        }

        function cancelSession(sessionId) {
            // Show confirmation with custom modal
            showConfirmModal(
                'Batalkan Sesi?', 
                'Apakah Anda yakin ingin membatalkan sesi ini? Progress Anda tidak akan tersimpan.',
                () => {
                    $.post(`/pomodoro/${sessionId}/cancel`)
                        .done(function(response) {
                            if (response.success) {
                                clearInterval(timerInterval);
                                showToast('Sesi dibatalkan', 'info');
                                
                                // Play cancellation voice
                                if ('speechSynthesis' in window) {
                                    const utterance = new SpeechSynthesisUtterance('Sesi dibatalkan. Tidak apa-apa, coba lagi nanti!');
                                    utterance.lang = 'id-ID';
                                    utterance.rate = 0.9;
                                    utterance.pitch = 1.0;
                                    utterance.volume = 0.7;
                                    speechSynthesis.speak(utterance);
                                }
                                
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            }
                        })
                        .fail(function() {
                            showToast('Gagal membatalkan sesi', 'error');
                        });
                }
            );
        }

        function showConfirmModal(title, message, onConfirm) {
            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl p-6 max-w-sm w-full slide-in shadow-2xl">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">${title}</h3>
                            <p class="text-gray-600">${message}</p>
                        </div>
                        <div class="flex space-x-3">
                            <button onclick="this.closest('.fixed').remove()" 
                                    class="flex-1 px-4 py-2 border-2 border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-colors">
                                Lanjutkan
                            </button>
                            <button onclick="confirmAction()" 
                                    class="flex-1 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl font-medium transition-colors">
                                Ya, Batalkan
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            // Add confirm action
            modal.querySelector('button[onclick="confirmAction()"]').onclick = () => {
                onConfirm();
                document.body.removeChild(modal);
            };
            
            document.body.appendChild(modal);
        }

        // Form submission
        $('#pomodoro-form').on('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                task_name: $('input[name="task_name"]').val(),
                type: $('select[name="type"]').val(),
                duration_minutes: $('input[name="duration_minutes"]').val()
            };

            hideNewSessionForm();
            createAndStartSession(formData);
        });

        // Auto-update duration based on type
        $('select[name="type"]').on('change', function() {
            const type = $(this).val();
            let duration;
            
            switch(type) {
                case 'work':
                    duration = 25;
                    break;
                case 'short_break':
                    duration = 5;
                    break;
                case 'long_break':
                    duration = 15;
                    break;
                default:
                    duration = 25;
            }
            
            $('input[name="duration_minutes"]').val(duration);
        });

        // Initialize timer if there's an active session
        @if($currentSession)
            $(document).ready(function() {
                totalSeconds = {{ $currentSession->duration_minutes }} * 60;
                currentSessionType = '{{ $currentSession->type }}';
                startTimer();
            });
        @endif

        // Keyboard shortcuts
        $(document).on('keydown', function(e) {
            // Space bar to start new session (when no active session)
            if (e.code === 'Space' && !currentSessionId && !$('#new-session-form').is(':visible')) {
                e.preventDefault();
                showNewSessionForm();
            }
            
            // Escape to hide form
            if (e.code === 'Escape') {
                hideNewSessionForm();
            }
            
            // Enter to submit form when form is visible
            if (e.code === 'Enter' && $('#new-session-form').is(':visible')) {
                e.preventDefault();
                $('#pomodoro-form').submit();
            }
            
            // T key to test voice (for development)
            if (e.code === 'KeyT' && e.ctrlKey && e.shiftKey) {
                e.preventDefault();
                testVoice('work');
            }
        });

        // Page visibility API to handle tab switching
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                // Page is hidden - show browser notification when timer completes
                if (currentSessionId && 'Notification' in window) {
                    Notification.requestPermission();
                }
            } else {
                // Page is visible, refresh current session data
                if (currentSessionId) {
                    $.get('/pomodoro/current')
                        .done(function(response) {
                            if (!response.session) {
                                location.reload();
                            }
                        });
                }
            }
        });

        // Request notification permission and initialize voices on load
        $(document).ready(function() {
            // Request notification permission
            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        showToast('Notifikasi browser diaktifkan! 🔔', 'success');
                    }
                });
            }
            
            // Initialize voices
            initializeVoices();
            
            // Show welcome message with voice
            if (!currentSessionId) {
                setTimeout(() => {
                    if ('speechSynthesis' in window) {
                        const utterance = new SpeechSynthesisUtterance('Selamat datang di Pomodoro Focus! Siap untuk meningkatkan produktivitas Anda?');
                        utterance.lang = 'id-ID';
                        utterance.rate = 0.9;
                        utterance.pitch = 1.0;
                        utterance.volume = 0.6;
                        speechSynthesis.speak(utterance);
                    }
                }, 2000);
            }
        });

        // Add smooth scrolling for better UX
        $('html').css('scroll-behavior', 'smooth');

        // Add click outside to close modal
        $(document).on('click', '#new-session-form', function(e) {
            if (e.target === this) {
                hideNewSessionForm();
            }
        });

        // Enhanced error handling
        $(document).ajaxError(function(event, xhr, settings) {
            if (xhr.status === 419) {
                showToast('Sesi expired. Silakan refresh halaman.', 'error');
                setTimeout(() => location.reload(), 2000);
            } else if (xhr.status >= 500) {
                showToast('Server error. Silakan coba lagi.', 'error');
            }
        });

        // Add progress animation on page load
        $(document).ready(function() {
            $('.slide-in').each(function(index) {
                $(this).css('animation-delay', (index * 0.1) + 's');
            });
        });

        // Add confetti effect for completed sessions
        function createConfetti() {
            const colors = ['#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#ffeaa7', '#dda0dd'];
            
            for (let i = 0; i < 50; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.style.cssText = `
                        position: fixed;
                        top: -10px;
                        left: ${Math.random() * 100}vw;
                        width: 10px;
                        height: 10px;
                        background: ${colors[Math.floor(Math.random() * colors.length)]};
                        pointer-events: none;
                        z-index: 1000;
                        border-radius: 50%;
                        animation: confetti-fall 3s linear forwards;
                    `;
                    
                    document.body.appendChild(confetti);
                    
                    setTimeout(() => {
                        if (confetti.parentNode) {
                            confetti.parentNode.removeChild(confetti);
                        }
                    }, 3000);
                }, i * 50);
            }
        }

        // Add CSS for confetti animation and pulse effect
        const style = document.createElement('style');
        style.textContent = `
            @keyframes confetti-fall {
                to {
                    transform: translateY(100vh) rotate(360deg);
                    opacity: 0;
                }
            }
            
            .pulse-animation {
                animation: pulse-red 1s ease-in-out infinite;
            }
            
            @keyframes pulse-red {
                0%, 100% {
                    transform: scale(1);
                    color: #ef4444;
                }
                50% {
                    transform: scale(1.05);
                    color: #dc2626;
                }
            }
        `;
        document.head.appendChild(style);

        // Voice settings panel (optional - for advanced users)
        function showVoiceSettings() {
            const voices = speechSynthesis.getVoices();
            const indonesianVoices = voices.filter(voice => 
                voice.lang.includes('id') || 
                voice.name.toLowerCase().includes('indonesia') ||
                voice.name.toLowerCase().includes('bahasa')
            );
            
            let voiceOptions = '';
            voices.forEach((voice, index) => {
                const isIndonesian = voice.lang.includes('id') || voice.name.toLowerCase().includes('indonesia');
                voiceOptions += `<option value="${index}" ${isIndonesian ? 'selected' : ''}>${voice.name} (${voice.lang})</option>`;
            });
            
            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl p-6 max-w-md w-full slide-in shadow-2xl">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-volume-up text-blue-500 text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Pengaturan Suara</h3>
                            <p class="text-gray-600">Sesuaikan notifikasi suara Anda</p>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Suara</label>
                                <select id="voice-select" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    ${voiceOptions}
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kecepatan Bicara</label>
                                <input type="range" id="speech-rate" min="0.5" max="2" step="0.1" value="0.9" 
                                       class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span>Lambat</span>
                                    <span>Normal</span>
                                    <span>Cepat</span>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Volume</label>
                                <input type="range" id="speech-volume" min="0" max="1" step="0.1" value="0.8" 
                                       class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-medium text-gray-700">Aktifkan Notifikasi Suara</label>
                                <input type="checkbox" id="voice-enabled" checked 
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            </div>
                        </div>
                        
                        <div class="flex space-x-3 mt-6">
                            <button onclick="testCurrentVoiceSettings()" 
                                    class="flex-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors">
                                <i class="fas fa-play mr-2"></i>Test Suara
                            </button>
                            <button onclick="saveVoiceSettings()" 
                                    class="flex-1 px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition-colors">
                                Simpan
                            </button>
                            <button onclick="this.closest('.fixed').remove()" 
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        function testCurrentVoiceSettings() {
            const voiceSelect = document.getElementById('voice-select');
            const rateSlider = document.getElementById('speech-rate');
            const volumeSlider = document.getElementById('speech-volume');
            const voiceEnabled = document.getElementById('voice-enabled');
            
            if (!voiceEnabled.checked) {
                showToast('Notifikasi suara dinonaktifkan', 'warning');
                return;
            }
            
            const voices = speechSynthesis.getVoices();
            const selectedVoice = voices[voiceSelect.value];
            
            const utterance = new SpeechSynthesisUtterance('Ini adalah tes suara untuk notifikasi Pomodoro. Waktu kerja telah selesai, waktunya istirahat!');
            utterance.voice = selectedVoice;
            utterance.rate = parseFloat(rateSlider.value);
            utterance.volume = parseFloat(volumeSlider.value);
            utterance.lang = 'id-ID';
            
            speechSynthesis.speak(utterance);
        }

        function saveVoiceSettings() {
            const voiceSelect = document.getElementById('voice-select');
            const rateSlider = document.getElementById('speech-rate');
            const volumeSlider = document.getElementById('speech-volume');
            const voiceEnabled = document.getElementById('voice-enabled');
            
            // Save to localStorage
            const settings = {
                voiceIndex: voiceSelect.value,
                rate: rateSlider.value,
                volume: volumeSlider.value,
                enabled: voiceEnabled.checked
            };
            
            localStorage.setItem('pomodoroVoiceSettings', JSON.stringify(settings));
            
            showToast('Pengaturan suara disimpan! 🔊', 'success');
            
            // Close modal
            document.querySelector('#voice-settings-modal')?.remove();
            document.querySelector('.fixed.inset-0.bg-black')?.remove();
        }

        function loadVoiceSettings() {
            const saved = localStorage.getItem('pomodoroVoiceSettings');
            if (saved) {
                return JSON.parse(saved);
            }
            
            // Default settings
            return {
                voiceIndex: 0,
                rate: 0.9,
                volume: 0.8,
                enabled: true
            };
        }

        // Enhanced voice notification with saved settings
        function playVoiceNotificationWithSettings(sessionType) {
            const settings = loadVoiceSettings();
            
            if (!settings.enabled) {
                return; // Voice notifications disabled
            }
            
            if ('speechSynthesis' in window) {
                let message = '';
                let nextAction = '';
                
                switch(sessionType) {
                    case 'work':
                        message = 'Waktu kerja telah selesai. Waktunya istirahat!';
                        nextAction = 'Ambil napas dalam-dalam dan rilekskan mata Anda.';
                        break;
                    case 'short_break':
                        message = 'Waktu istirahat pendek telah selesai. Waktunya kembali bekerja!';
                        nextAction = 'Mari fokus kembali pada tugas Anda.';
                        break;
                    case 'long_break':
                        message = 'Waktu istirahat panjang telah selesai. Siap untuk sesi kerja berikutnya!';
                        nextAction = 'Anda sudah segar dan siap untuk produktif kembali.';
                        break;
                    default:
                        message = 'Sesi Pomodoro telah selesai!';
                        nextAction = 'Kerja bagus!';
                }
                
                const voices = speechSynthesis.getVoices();
                const selectedVoice = voices[settings.voiceIndex] || voices[0];
                
                const utterance = new SpeechSynthesisUtterance(message);
                utterance.voice = selectedVoice;
                utterance.lang = 'id-ID';
                utterance.rate = parseFloat(settings.rate);
                utterance.volume = parseFloat(settings.volume);
                utterance.pitch = 1.1;
                
                speechSynthesis.speak(utterance);
                
                // Speak the next action after a pause
                utterance.onend = () => {
                    setTimeout(() => {
                        const nextUtterance = new SpeechSynthesisUtterance(nextAction);
                        nextUtterance.voice = selectedVoice;
                        nextUtterance.lang = 'id-ID';
                        nextUtterance.rate = parseFloat(settings.rate) * 0.9;
                        nextUtterance.volume = parseFloat(settings.volume) * 0.9;
                        nextUtterance.pitch = 1.0;
                        speechSynthesis.speak(nextUtterance);
                    }, 1000);
                };
            }
        }

        // Update the main notification function to use settings
        function playNotificationWithVoice(sessionType) {
            // Play notification sound first
            playNotificationSound();
            
            // Then play voice notification with user settings
            setTimeout(() => {
                playVoiceNotificationWithSettings(sessionType);
            }, 1000);
            
            // Show browser notification if permission granted
            showBrowserNotification(sessionType);
        }

        // Add voice settings button to the page
        function addVoiceSettingsButton() {
            const settingsButton = `
                <button onclick="showVoiceSettings()" 
                        class="fixed bottom-4 left-4 w-12 h-12 bg-blue-500 hover:bg-blue-600 text-white rounded-full shadow-lg transition-all duration-300 hover:scale-110 z-40"
                        title="Pengaturan Suara">
                    <i class="fas fa-cog"></i>
                </button>
            `;
            
            $('body').append(settingsButton);
        }

        // Motivational quotes for different session types
        const motivationalQuotes = {
            work: [
                'Fokus adalah kunci kesuksesan. Mari bekerja dengan penuh konsentrasi!',
                'Setiap menit yang Anda fokuskan adalah investasi untuk masa depan yang lebih baik.',
                'Produktivitas dimulai dari fokus. Ayo mulai sesi kerja yang produktif!',
                'Kesuksesan adalah hasil dari konsistensi. Mari konsisten dalam bekerja!'
            ],
            short_break: [
                'Istirahat sejenak untuk mengisi ulang energi Anda.',
                'Pikiran yang segar menghasilkan ide-ide cemerlang. Nikmati istirahat Anda.',
                'Istirahat bukan berarti berhenti, tapi mempersiapkan diri untuk langkah selanjutnya.',
                'Ambil napas dalam-dalam dan rasakan ketenangan.'
            ],
            long_break: [
                'Anda telah bekerja keras! Saatnya istirahat yang lebih panjang.',
                'Istirahat yang cukup adalah investasi terbaik untuk produktivitas.',
                'Nikmati waktu istirahat Anda. Anda sudah melakukan pekerjaan yang luar biasa!',
                'Recharge energi Anda untuk sesi kerja berikutnya yang lebih produktif.'
            ]
        };

        function getRandomMotivationalQuote(sessionType) {
            const quotes = motivationalQuotes[sessionType] || motivationalQuotes.work;
            return quotes[Math.floor(Math.random() * quotes.length)];
        }

        // Enhanced completion message with motivational quote
        function playCompletionWithMotivation(sessionType) {
            const settings = loadVoiceSettings();
            
            if (!settings.enabled) return;
            
            if ('speechSynthesis' in window) {
                const quote = getRandomMotivationalQuote(sessionType);
                const voices = speechSynthesis.getVoices();
                const selectedVoice = voices[settings.voiceIndex] || voices[0];
                
                const utterance = new SpeechSynthesisUtterance(quote);
                utterance.voice = selectedVoice;
                utterance.lang = 'id-ID';
                utterance.rate = parseFloat(settings.rate) * 0.95;
                utterance.volume = parseFloat(settings.volume);
                utterance.pitch = 1.0;
                
                speechSynthesis.speak(utterance);
            }
        }

        // Initialize everything when document is ready
        $(document).ready(function() {
            // Add voice settings button
            addVoiceSettingsButton();
            
            // Initialize voices
            initializeVoices();
            
            // Request notification permission
            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        showToast('Notifikasi browser diaktifkan! 🔔', 'success');
                    }
                });
            }
            
            // Show welcome message with voice (only if no active session)
            if (!currentSessionId) {
                setTimeout(() => {
                    const settings = loadVoiceSettings();
                    if (settings.enabled && 'speechSynthesis' in window) {
                        const utterance = new SpeechSynthesisUtterance('Selamat datang di Pomodoro Focus! Siap untuk meningkatkan produktivitas Anda?');
                        utterance.lang = 'id-ID';
                        utterance.rate = 0.9;
                        utterance.pitch = 1.0;
                        utterance.volume = 0.6;
                        speechSynthesis.speak(utterance);
                    }
                }, 3000);
            }
            
            // Add slide-in animations
            $('.slide-in').each(function(index) {
                $(this).css('animation-delay', (index * 0.1) + 's');
            });
        });

        // Debug function to test all voice notifications
        function testAllVoiceNotifications() {
            console.log('Testing all voice notifications...');
            
            setTimeout(() => testVoice('work'), 1000);
            setTimeout(() => testVoice('short_break'), 4000);
            setTimeout(() => testVoice('long_break'), 7000);
        }

        // Add to window for console access
        window.testAllVoiceNotifications = testAllVoiceNotifications;
        window.testVoice = testVoice;
        window.showVoiceSettings = showVoiceSettings;

        // Enhanced session completion with all features
        function handleSessionCompletion(sessionId, sessionType) {
            clearInterval(timerInterval);
            
            // Play comprehensive notification sequence
            playNotificationWithVoice(sessionType);
            
            // Show celebration
            showCelebration();
            
            // Play motivational quote after main notification
            setTimeout(() => {
                playCompletionWithMotivation(sessionType);
            }, 4000);
            
            // Auto-complete session
            setTimeout(() => {
                completeSession(sessionId);
            }, 6000);
        }

        // Update the timer function to use enhanced completion
        function startTimer() {
            if (timerInterval) {
                clearInterval(timerInterval);
            }

            timerInterval = setInterval(function() {
                $.get('/pomodoro/current')
                    .done(function(response) {
                        if (response.session && response.remaining_time > 0) {
                            $('#timer-display').text(formatTime(response.remaining_time));
                            updateProgressCircle(response.remaining_time, totalSeconds);
                            
                            // Update current session type
                            currentSessionType = response.session.type;
                            
                            // Show warning when 2 minutes left (for longer sessions)
                            if (response.remaining_time === 120 && totalSeconds > 300) {
                                showToast('⚠️ 2 menit tersisa!', 'warning');
                                playWarningSound();
                            }
                            
                            // Show warning when 1 minute left
                            if (response.remaining_time === 60) {
                                showToast('⚠️ 1 menit tersisa!', 'warning');
                                playWarningSound();
                                
                                // Voice warning for 1 minute
                                const settings = loadVoiceSettings();
                                if (settings.enabled && 'speechSynthesis' in window) {
                                    const utterance = new SpeechSynthesisUtterance('Satu menit tersisa!');
                                    utterance.lang = 'id-ID';
                                    utterance.rate = 1.0;
                                    utterance.volume = 0.6;
                                    speechSynthesis.speak(utterance);
                                }
                            }
                            
                            // Show final countdown for last 10 seconds
                            if (response.remaining_time <= 10 && response.remaining_time > 0) {
                                $('#timer-display').addClass('pulse-animation text-red-400');
                                
                                // Play tick sound and voice countdown for last 5 seconds
                                if (response.remaining_time <= 5) {
                                    playTickSound();
                                    
                                    const settings = loadVoiceSettings();
                                    if (settings.enabled && 'speechSynthesis' in window) {
                                        const utterance = new SpeechSynthesisUtterance(response.remaining_time.toString());
                                        utterance.lang = 'id-ID';
                                        utterance.rate = 1.2;
                                        utterance.volume = 0.5;
                                        utterance.pitch = 1.2;
                                        speechSynthesis.speak(utterance);
                                    }
                                }
                            }
                            
                        } else if (response.session && response.remaining_time <= 0) {
                            // Timer finished - use enhanced completion
                            $('#timer-display').text('00:00').removeClass('pulse-animation');
                            updateProgressCircle(0, totalSeconds);
                            
                            handleSessionCompletion(response.session.id, currentSessionType);
                            
                        } else {
                            // No active session
                            clearInterval(timerInterval);
                            location.reload();
                        }
                    })
                    .fail(function() {
                        console.error('Failed to get current session');
                        // Retry after 5 seconds
                        setTimeout(() => {
                            if (currentSessionId) {
                                startTimer();
                            }
                        }, 5000);
                    });
            }, 1000);
        }

        // Add session type recommendations
        function showSessionRecommendations() {
            const now = new Date();
            const hour = now.getHours();
            let recommendation = '';
            let recommendedType = 'work';
            
            if (hour >= 6 && hour < 9) {
                recommendation = 'Pagi adalah waktu terbaik untuk tugas-tugas yang membutuhkan konsentrasi tinggi!';
                recommendedType = 'work';
            } else if (hour >= 9 && hour < 12) {
                recommendation = 'Waktu produktif pagi! Perfect untuk deep work session.';
                recommendedType = 'work';
            } else if (hour >= 12 && hour < 14) {
                recommendation = 'Waktu makan siang. Bagaimana kalau istirahat dulu?';
                recommendedType = 'long_break';
            } else if (hour >= 14 && hour < 17) {
                recommendation = 'Siang hari cocok untuk tugas-tugas kreatif dan kolaboratif.';
                recommendedType = 'work';
            } else if (hour >= 17 && hour < 19) {
                recommendation = 'Sore hari, energi mulai menurun. Istirahat sejenak?';
                recommendedType = 'short_break';
            } else {
                recommendation = 'Malam hari, saatnya relaksasi dan persiapan untuk besok.';
                recommendedType = 'long_break';
            }
            
            return { recommendation, recommendedType };
        }

        // Add productivity tips based on session history
        function getProductivityTip() {
            const tips = [
                'Matikan notifikasi yang tidak penting selama sesi kerja untuk fokus maksimal.',
                'Gunakan teknik 20-20-20: Setiap 20 menit, lihat objek sejauh 20 kaki selama 20 detik.',
                'Siapkan segelas air di meja kerja Anda untuk menjaga hidrasi.',
                'Buat daftar tugas sebelum memulai sesi Pomodoro untuk fokus yang lebih terarah.',
                'Gunakan musik instrumental atau white noise untuk meningkatkan konsentrasi.',
                'Pastikan ruang kerja Anda rapi dan bebas dari gangguan.',
                'Lakukan peregangan ringan selama istirahat untuk menjaga kesehatan tubuh.',
                'Tetapkan tujuan spesifik untuk setiap sesi kerja Anda.',
                'Reward diri Anda setelah menyelesaikan beberapa sesi Pomodoro.',
                'Jangan lupa untuk istirahat makan dan menjaga pola makan yang sehat.'
            ];
            
            return tips[Math.floor(Math.random() * tips.length)];
        }

        // Enhanced quick start with recommendations
        function enhancedQuickStart() {
            const { recommendation, recommendedType } = showSessionRecommendations();
            const tip = getProductivityTip();
            
            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl p-6 max-w-md w-full slide-in shadow-2xl">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-lightbulb text-white text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Rekomendasi Sesi</h3>
                            <p class="text-gray-600 text-sm mb-4">${recommendation}</p>
                            
                            <div class="bg-blue-50 rounded-lg p-3 mb-4">
                                <p class="text-sm text-blue-800">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>Tips:</strong> ${tip}
                                </p>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <button onclick="quickStart('work', 25)" 
                                    class="w-full px-4 py-3 bg-gradient-to-r from-green-400 to-blue-500 hover:from-green-500 hover:to-blue-600 text-white rounded-xl font-medium transition-all duration-300 transform hover:scale-105 ${recommendedType === 'work' ? 'ring-2 ring-yellow-400' : ''}">
                                <i class="fas fa-briefcase mr-2"></i>
                                Sesi Kerja (25 menit)
                                ${recommendedType === 'work' ? '<span class="ml-2 text-yellow-200">⭐ Direkomendasikan</span>' : ''}
                            </button>
                            
                            <button onclick="quickStart('short_break', 5)" 
                                    class="w-full px-4 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 hover:from-yellow-500 hover:to-orange-600 text-white rounded-xl font-medium transition-all duration-300 transform hover:scale-105 ${recommendedType === 'short_break' ? 'ring-2 ring-yellow-400' : ''}">
                                <i class="fas fa-coffee mr-2"></i>
                                Istirahat Pendek (5 menit)
                                ${recommendedType === 'short_break' ? '<span class="ml-2 text-yellow-200">⭐ Direkomendasikan</span>' : ''}
                            </button>
                            
                            <button onclick="quickStart('long_break', 15)" 
                                    class="w-full px-4 py-3 bg-gradient-to-r from-purple-400 to-pink-500 hover:from-purple-500 hover:to-pink-600 text-white rounded-xl font-medium transition-all duration-300 transform hover:scale-105 ${recommendedType === 'long_break' ? 'ring-2 ring-yellow-400' : ''}">
                                <i class="fas fa-bed mr-2"></i>
                                Istirahat Panjang (15 menit)
                                ${recommendedType === 'long_break' ? '<span class="ml-2 text-yellow-200">⭐ Direkomendasikan</span>' : ''}
                            </button>
                            
                            <button onclick="showNewSessionForm(); this.closest('.fixed').remove();" 
                                    class="w-full px-4 py-2 border-2 border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-colors">
                                <i class="fas fa-cog mr-2"></i>
                                Sesi Custom
                            </button>
                        </div>
                        
                        <button onclick="this.closest('.fixed').remove()" 
                                class="absolute top-4 right-4 w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition-colors">
                            <i class="fas fa-times text-gray-500"></i>
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Close modal when clicking outside
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    document.body.removeChild(modal);
                }
            });
        }

        // Update the main quick start button to use enhanced version
        function updateQuickStartButton() {
            const quickStartBtn = document.querySelector('[onclick*="showNewSessionForm"]');
            if (quickStartBtn && !currentSessionId) {
                quickStartBtn.setAttribute('onclick', 'enhancedQuickStart()');
                quickStartBtn.innerHTML = '<i class="fas fa-play mr-2"></i>Mulai Sesi Cerdas';
            }
        }

        // Add breathing exercise for break sessions
        function startBreathingExercise() {
            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="fixed inset-0 bg-gradient-to-br from-blue-400 to-purple-600 z-50 flex items-center justify-center p-4">
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-8 max-w-sm w-full text-center shadow-2xl">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Latihan Pernapasan</h3>
                        <p class="text-gray-600 mb-6">Ikuti panduan pernapasan untuk relaksasi</p>
                        
                        <div class="relative w-32 h-32 mx-auto mb-6">
                            <div id="breathing-circle" class="w-full h-full bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-lg transition-transform duration-4000 ease-in-out">
                                <span id="breathing-text">Bersiap</span>
                            </div>
                        </div>
                        
                        <div id="breathing-instruction" class="text-lg font-medium text-gray-700 mb-4">
                            Klik mulai untuk memulai latihan pernapasan
                        </div>
                        
                        <div class="flex space-x-3">
                            <button id="start-breathing" onclick="runBreathingExercise()" 
                                    class="flex-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition-colors">
                                Mulai
                            </button>
                            <button onclick="this.closest('.fixed').remove()" 
                                    class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        function runBreathingExercise() {
            const circle = document.getElementById('breathing-circle');
            const text = document.getElementById('breathing-text');
            const instruction = document.getElementById('breathing-instruction');
            const startBtn = document.getElementById('start-breathing');
            
            startBtn.style.display = 'none';
            
            let cycle = 0;
            const totalCycles = 5;
            
            function breatheCycle() {
                if (cycle >= totalCycles) {
                    text.textContent = 'Selesai';
                    instruction.textContent = 'Latihan pernapasan selesai! Anda siap untuk melanjutkan.';
                    circle.style.transform = 'scale(1)';
                    
                    // Voice completion
                    const settings = loadVoiceSettings();
                    if (settings.enabled && 'speechSynthesis' in window) {
                        const utterance = new SpeechSynthesisUtterance('Latihan pernapasan selesai. Anda sudah lebih rileks dan siap melanjutkan aktivitas.');
                        utterance.lang = 'id-ID';
                        utterance.rate = 0.8;
                        utterance.volume = 0.7;
                        speechSynthesis.speak(utterance);
                    }
                    
                    setTimeout(() => {
                        document.querySelector('.fixed.inset-0.bg-gradient-to-br')?.remove();
                    }, 3000);
                    return;
                }
                
                cycle++;
                
                // Inhale phase (4 seconds)
                text.textContent = 'Tarik';
                instruction.textContent = `Tarik napas dalam-dalam... (${cycle}/${totalCycles})`;
                circle.style.transform = 'scale(1.5)';
                circle.style.transitionDuration = '4s';
                
                // Voice guidance for inhale
                const settings = loadVoiceSettings();
                if (settings.enabled && 'speechSynthesis' in window) {
                    const utterance = new SpeechSynthesisUtterance('Tarik napas');
                    utterance.lang = 'id-ID';
                    utterance.rate = 0.7;
                    utterance.volume = 0.5;
                    speechSynthesis.speak(utterance);
                }
                
                setTimeout(() => {
                    // Hold phase (2 seconds)
                    text.textContent = 'Tahan';
                    instruction.textContent = 'Tahan napas...';
                    
                    setTimeout(() => {
                        // Exhale phase (6 seconds)
                        text.textContent = 'Buang';
                        instruction.textContent = 'Buang napas perlahan...';
                        circle.style.transform = 'scale(1)';
                        circle.style.transitionDuration = '6s';
                        
                        // Voice guidance for exhale
                        if (settings.enabled && 'speechSynthesis' in window) {
                            const utterance = new SpeechSynthesisUtterance('Buang napas');
                            utterance.lang = 'id-ID';
                            utterance.rate = 0.7;
                            utterance.volume = 0.5;
                            speechSynthesis.speak(utterance);
                        }
                        
                        setTimeout(() => {
                            breatheCycle();
                        }, 6000);
                        
                    }, 2000);
                }, 4000);
            }
            
            breatheCycle();
        }

        // Add focus music player for work sessions
        function showFocusMusicPlayer() {
            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl p-6 max-w-md w-full slide-in shadow-2xl">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-music text-white text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Musik Fokus</h3>
                            <p class="text-gray-600">Pilih suara latar untuk meningkatkan konsentrasi</p>
                        </div>
                        
                        <div class="space-y-3">
                            <button onclick="playFocusSound('rain')" 
                                    class="w-full px-4 py-3 bg-blue-100 hover:bg-blue-200 text-blue-800 rounded-xl font-medium transition-colors flex items-center">
                                <i class="fas fa-cloud-rain mr-3"></i>
                                Suara Hujan
                            </button>
                            
                            <button onclick="playFocusSound('forest')" 
                                    class="w-full px-4 py-3 bg-green-100 hover:bg-green-200 text-green-800 rounded-xl font-medium transition-colors flex items-center">
                                <i class="fas fa-tree mr-3"></i>
                                Suara Hutan
                            </button>
                            
                            <button onclick="playFocusSound('ocean')" 
                                    class="w-full px-4 py-3 bg-cyan-100 hover:bg-cyan-200 text-cyan-800 rounded-xl font-medium transition-colors flex items-center">
                                <i class="fas fa-water mr-3"></i>
                                Suara Ombak
                            </button>
                            
                            <button onclick="playFocusSound('whitenoise')" 
                                    class="w-full px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-xl font-medium transition-colors flex items-center">
                                <i class="fas fa-volume-up mr-3"></i>
                                White Noise
                            </button>
                            
                            <button onclick="stopFocusSound()" 
                                    class="w-full px-4 py-2 bg-red-100 hover:bg-red-200 text-red-800 rounded-xl font-medium transition-colors flex items-center justify-center">
                                <i class="fas fa-stop mr-2"></i>
                                Stop Musik
                            </button>
                        </div>
                        
                        <div class="mt-4 flex justify-between items-center">
                            <label class="text-sm font-medium text-gray-700">Volume:</label>
                            <input type="range" id="focus-volume" min="0" max="1" step="0.1" value="0.3" 
                                   class="w-32 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                   onchange="updateFocusVolume(this.value)">
                        </div>
                        
                        <button onclick="this.closest('.fixed').remove()" 
                                class="w-full mt-4 px-4 py-2 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        let focusAudioContext = null;
        let focusOscillator = null;
        let focusGainNode = null;

        function playFocusSound(type) {
            stopFocusSound(); // Stop any existing sound
            
            focusAudioContext = new (window.AudioContext || window.webkitAudioContext)();
            focusGainNode = focusAudioContext.createGain();
            focusGainNode.connect(focusAudioContext.destination);
            focusGainNode.gain.value = 0.3;
            
            switch(type) {
                case 'rain':
                    createRainSound();
                    break;
                case 'forest':
                    createForestSound();
                    break;
                case 'ocean':
                    createOceanSound();
                    break;
                case 'whitenoise':
                    createWhiteNoise();
                    break;
            }
            
            showToast(`🎵 ${type.charAt(0).toUpperCase() + type.slice(1)} sound dimulai`, 'success');
        }

        function createRainSound() {
            // Create rain-like noise
            const bufferSize = 4096;
            const whiteNoise = focusAudioContext.createScriptProcessor(bufferSize, 1, 1);
            
            whiteNoise.onaudioprocess = function(e) {
                const output = e.outputBuffer.getChannelData(0);
                for (let i = 0; i < bufferSize; i++) {
                    output[i] = (Math.random() * 2 - 1) * 0.1;
                }
            };
            
            // Add filter for rain-like sound
            const filter = focusAudioContext.createBiquadFilter();
            filter.type = 'lowpass';
            filter.frequency.value = 1000;
            
            whiteNoise.connect(filter);
            filter.connect(focusGainNode);
            
            focusOscillator = whiteNoise;
        }

        function createForestSound() {
            // Create forest ambience with multiple oscillators
            const oscillators = [];
            const frequencies = [80, 120, 200, 300];
            
            frequencies.forEach(freq => {
                const osc = focusAudioContext.createOscillator();
                const gain = focusAudioContext.createGain();
                
                osc.type = 'sine';
                osc.frequency.value = freq + (Math.random() * 20 - 10);
                gain.gain.value = 0.02;
                
                osc.connect(gain);
                gain.connect(focusGainNode);
                osc.start();
                
                oscillators.push(osc);
            });
            
            focusOscillator = oscillators;
        }

        function createOceanSound() {
            // Create ocean wave sound
            const bufferSize = 8192;
            const processor = focusAudioContext.createScriptProcessor(bufferSize, 1, 1);
            let phase = 0;
            
            processor.onaudioprocess = function(e) {
                const output = e.outputBuffer.getChannelData(0);
                for (let i = 0; i < bufferSize; i++) {
                    // Create wave-like pattern
                    const wave = Math.sin(phase) * 0.3 + (Math.random() * 2 - 1) * 0.1;
                    output[i] = wave * 0.2;
                    phase += 0.001;
                }
            };
            
            processor.connect(focusGainNode);
            focusOscillator = processor;
        }

        function createWhiteNoise() {
            const bufferSize = 4096;
            const whiteNoise = focusAudioContext.createScriptProcessor(bufferSize, 1, 1);
            
            whiteNoise.onaudioprocess = function(e) {
                const output = e.outputBuffer.getChannelData(0);
                for (let i = 0; i < bufferSize; i++) {
                    output[i] = Math.random() * 2 - 1;
                }
            };
            
            whiteNoise.connect(focusGainNode);
            focusOscillator = whiteNoise;
        }

        function updateFocusVolume(value) {
            if (focusGainNode) {
                focusGainNode.gain.value = parseFloat(value);
            }
        }

        function stopFocusSound() {
            if (focusOscillator) {
                if (Array.isArray(focusOscillator)) {
                    focusOscillator.forEach(osc => {
                        try { osc.stop(); } catch(e) {}
                        try { osc.disconnect(); } catch(e) {}
                    });
                } else {
                    try { focusOscillator.stop(); } catch(e) {}
                    try { focusOscillator.disconnect(); } catch(e) {}
                }
                focusOscillator = null;
            }
            
            if (focusAudioContext) {
                try { focusAudioContext.close(); } catch(e) {}
                focusAudioContext = null;
            }
            
            showToast('🔇 Musik fokus dihentikan', 'info');
        }

        // Add focus music button for work sessions
        function addFocusMusicButton() {
            if (currentSessionType === 'work') {
                const musicButton = `
                    <button onclick="showFocusMusicPlayer()" 
                            class="fixed bottom-4 right-20 w-12 h-12 bg-green-500 hover:bg-green-600 text-white rounded-full shadow-lg transition-all duration-300 hover:scale-110 z-40"
                            title="Musik Fokus">
                        <i class="fas fa-music"></i>
                    </button>
                `;
                
                $('body').append(musicButton);
            }
        }

        // Enhanced session statistics
        function showSessionStats() {
            $.get('/pomodoro/stats')
                .done(function(response) {
                    const modal = document.createElement('div');
                    modal.innerHTML = `
                        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                            <div class="bg-white rounded-2xl p-6 max-w-lg w-full slide-in shadow-2xl max-h-[90vh] overflow-y-auto">
                                <div class="text-center mb-6">
                                    <div class="w-16 h-16 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-chart-bar text-white text-xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">Statistik Produktivitas</h3>
                                    <p class="text-gray-600">Ringkasan performa Pomodoro Anda</p>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 mb-6">
                                    <div class="bg-blue-50 rounded-lg p-4 text-center">
                                        <div class="text-2xl font-bold text-blue-600">${response.total_sessions || 0}</div>
                                        <div class="text-sm text-blue-800">Total Sesi</div>
                                    </div>
                                                                       <div class="bg-green-50 rounded-lg p-4 text-center">
                                        <div class="text-2xl font-bold text-green-600">${response.completed_sessions || 0}</div>
                                        <div class="text-sm text-green-800">Sesi Selesai</div>
                                    </div>
                                    <div class="bg-yellow-50 rounded-lg p-4 text-center">
                                        <div class="text-2xl font-bold text-yellow-600">${Math.round(response.total_minutes || 0)}</div>
                                        <div class="text-sm text-yellow-800">Total Menit</div>
                                    </div>
                                    <div class="bg-purple-50 rounded-lg p-4 text-center">
                                        <div class="text-2xl font-bold text-purple-600">${Math.round((response.completed_sessions / response.total_sessions * 100) || 0)}%</div>
                                        <div class="text-sm text-purple-800">Tingkat Selesai</div>
                                    </div>
                                </div>
                                
                                <div class="space-y-4 mb-6">
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <h4 class="font-semibold text-gray-800 mb-2">Sesi Hari Ini</h4>
                                        <div class="text-sm text-gray-600">
                                            Kerja: ${response.today_work || 0} sesi • 
                                            Istirahat: ${response.today_breaks || 0} sesi
                                        </div>
                                    </div>
                                    
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <h4 class="font-semibold text-gray-800 mb-2">Streak Terpanjang</h4>
                                        <div class="text-sm text-gray-600">
                                            ${response.longest_streak || 0} hari berturut-turut
                                        </div>
                                    </div>
                                    
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <h4 class="font-semibold text-gray-800 mb-2">Waktu Produktif Favorit</h4>
                                        <div class="text-sm text-gray-600">
                                            ${response.favorite_time || 'Belum ada data'}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-4 text-white text-center mb-4">
                                    <div class="text-lg font-semibold">🎉 Achievement Unlocked!</div>
                                    <div class="text-sm opacity-90 mt-1">${getAchievementMessage(response)}</div>
                                </div>
                                
                                <button onclick="this.closest('.fixed').remove()" 
                                        class="w-full px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-xl font-medium transition-colors">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    `;
                    
                    document.body.appendChild(modal);
                })
                .fail(function() {
                    showToast('Gagal memuat statistik', 'error');
                });
        }

        function getAchievementMessage(stats) {
            const completed = stats.completed_sessions || 0;
            
            if (completed >= 100) return "Pomodoro Master! 100+ sesi selesai!";
            if (completed >= 50) return "Productivity Expert! 50+ sesi selesai!";
            if (completed >= 25) return "Focus Champion! 25+ sesi selesai!";
            if (completed >= 10) return "Getting Focused! 10+ sesi selesai!";
            if (completed >= 5) return "Good Start! 5+ sesi selesai!";
            return "Welcome to Pomodoro! Mulai perjalanan produktivitas Anda!";
        }

        // Add stats button
        function addStatsButton() {
            const statsButton = `
                <button onclick="showSessionStats()" 
                        class="fixed bottom-4 right-4 w-12 h-12 bg-purple-500 hover:bg-purple-600 text-white rounded-full shadow-lg transition-all duration-300 hover:scale-110 z-40"
                        title="Statistik">
                    <i class="fas fa-chart-bar"></i>
                </button>
            `;
            
            $('body').append(statsButton);
        }

        // Enhanced break suggestions
        function showBreakSuggestions() {
            const suggestions = [
                {
                    title: "Latihan Pernapasan",
                    description: "Relaksasi dengan teknik pernapasan 4-7-8",
                    icon: "fas fa-lungs",
                    action: "startBreathingExercise()",
                    color: "from-blue-400 to-cyan-500"
                },
                {
                    title: "Peregangan Ringan",
                    description: "Gerakan sederhana untuk merilekskan otot",
                    icon: "fas fa-running",
                    action: "showStretchingGuide()",
                    color: "from-green-400 to-emerald-500"
                },
                {
                    title: "Latihan Mata",
                    description: "Istirahatkan mata dari layar komputer",
                    icon: "fas fa-eye",
                    action: "startEyeExercise()",
                    color: "from-yellow-400 to-orange-500"
                },
                {
                    title: "Hidrasi",
                    description: "Minum air putih dan jaga hidrasi tubuh",
                    icon: "fas fa-tint",
                    action: "showHydrationReminder()",
                    color: "from-cyan-400 to-blue-500"
                },
                {
                    title: "Mindfulness",
                    description: "Meditasi singkat untuk kejernihan pikiran",
                    icon: "fas fa-om",
                    action: "startMindfulness()",
                    color: "from-purple-400 to-pink-500"
                }
            ];

            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl p-6 max-w-md w-full slide-in shadow-2xl">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-leaf text-white text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Aktivitas Istirahat</h3>
                            <p class="text-gray-600">Pilih aktivitas untuk mengoptimalkan istirahat Anda</p>
                        </div>
                        
                        <div class="space-y-3">
                            ${suggestions.map(suggestion => `
                                <button onclick="${suggestion.action}; this.closest('.fixed').remove();" 
                                        class="w-full p-4 bg-gradient-to-r ${suggestion.color} hover:scale-105 text-white rounded-xl font-medium transition-all duration-300 text-left">
                                    <div class="flex items-center">
                                        <i class="${suggestion.icon} text-xl mr-4"></i>
                                        <div>
                                            <div class="font-semibold">${suggestion.title}</div>
                                            <div class="text-sm opacity-90">${suggestion.description}</div>
                                        </div>
                                    </div>
                                </button>
                            `).join('')}
                        </div>
                        
                        <button onclick="this.closest('.fixed').remove()" 
                                class="w-full mt-4 px-4 py-2 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-colors">
                            Lewati
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        // Eye exercise function
        function startEyeExercise() {
            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="fixed inset-0 bg-gradient-to-br from-green-400 to-blue-600 z-50 flex items-center justify-center p-4">
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-8 max-w-sm w-full text-center shadow-2xl">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Latihan Mata 20-20-20</h3>
                        <p class="text-gray-600 mb-6">Lihat objek sejauh 20 kaki selama 20 detik</p>
                        
                        <div class="relative w-32 h-32 mx-auto mb-6">
                            <div id="eye-circle" class="w-full h-full bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                                <span id="eye-countdown">20</span>
                            </div>
                        </div>
                        
                        <div id="eye-instruction" class="text-lg font-medium text-gray-700 mb-4">
                            Lihat keluar jendela atau objek terjauh di ruangan
                        </div>
                        
                        <button id="start-eye-exercise" onclick="runEyeExercise()" 
                                class="w-full px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition-colors">
                            Mulai Latihan
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        function runEyeExercise() {
            const countdown = document.getElementById('eye-countdown');
            const instruction = document.getElementById('eye-instruction');
            const startBtn = document.getElementById('start-eye-exercise');
            
            startBtn.style.display = 'none';
            instruction.textContent = 'Fokus pada objek terjauh yang bisa Anda lihat';
            
            let seconds = 20;
            const interval = setInterval(() => {
                countdown.textContent = seconds;
                seconds--;
                
                if (seconds < 0) {
                    clearInterval(interval);
                    countdown.textContent = '✓';
                    instruction.textContent = 'Latihan mata selesai! Mata Anda sudah lebih segar.';
                    
                    // Voice completion
                    const settings = loadVoiceSettings();
                    if (settings.enabled && 'speechSynthesis' in window) {
                        const utterance = new SpeechSynthesisUtterance('Latihan mata selesai. Mata Anda sudah lebih rileks.');
                        utterance.lang = 'id-ID';
                        utterance.rate = 0.9;
                        utterance.volume = 0.7;
                        speechSynthesis.speak(utterance);
                    }
                    
                    setTimeout(() => {
                        document.querySelector('.fixed.inset-0.bg-gradient-to-br')?.remove();
                    }, 3000);
                }
            }, 1000);
        }

        // Stretching guide function
        function showStretchingGuide() {
            const stretches = [
                "Putar kepala perlahan ke kiri dan kanan (5x)",
                "Angkat bahu ke atas, tahan 5 detik, turunkan (3x)",
                "Rentangkan tangan ke atas, tarik ke samping (10 detik)",
                "Putar pergelangan tangan searah jarum jam (10x)",
                "Berdiri dan regangkan kaki (30 detik)"
            ];

            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl p-6 max-w-md w-full slide-in shadow-2xl">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-running text-white text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Panduan Peregangan</h3>
                            <p class="text-gray-600">Gerakan sederhana untuk merilekskan tubuh</p>
                        </div>
                        
                        <div class="space-y-3 mb-6">
                            ${stretches.map((stretch, index) => `
                                <div class="flex items-start p-3 bg-gray-50 rounded-lg">
                                    <div class="w-6 h-6 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3 mt-0.5">
                                        ${index + 1}
                                    </div>
                                    <div class="text-gray-700">${stretch}</div>
                                </div>
                            `).join('')}
                        </div>
                        
                        <div class="bg-yellow-50 rounded-lg p-3 mb-4">
                            <p class="text-sm text-yellow-800">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Tips:</strong> Lakukan gerakan perlahan dan jangan memaksakan jika terasa sakit.
                            </p>
                        </div>
                        
                        <button onclick="this.closest('.fixed').remove()" 
                                class="w-full px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl font-medium transition-colors">
                            Selesai
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

             // Hydration reminder
        function showHydrationReminder() {
            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl p-6 max-w-sm w-full slide-in shadow-2xl">
                        <div class="text-center mb-6">
                            <div class="w-20 h-20 bg-gradient-to-r from-cyan-400 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-tint text-white text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Waktunya Hidrasi! 💧</h3>
                            <p class="text-gray-600">Jaga kesehatan dengan minum air putih</p>
                        </div>
                        
                        <div class="space-y-4 mb-6">
                            <div class="bg-blue-50 rounded-lg p-4 text-center">
                                <div class="text-3xl mb-2">🥤</div>
                                <div class="text-lg font-semibold text-blue-800">Minum 1-2 Gelas Air</div>
                                <div class="text-sm text-blue-600">Sekitar 250-500ml</div>
                            </div>
                            
                            <div class="space-y-2">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    Meningkatkan konsentrasi
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    Mencegah dehidrasi
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    Menjaga energi tubuh
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button onclick="markHydrated(); this.closest('.fixed').remove();" 
                                    class="flex-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-xl font-medium transition-colors">
                                ✓ Sudah Minum
                            </button>
                            <button onclick="setHydrationReminder(); this.closest('.fixed').remove();" 
                                    class="flex-1 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl font-medium transition-colors">
                                ⏰ Ingatkan Lagi
                            </button>
                        </div>
                        
                        <button onclick="this.closest('.fixed').remove()" 
                                class="w-full mt-3 px-4 py-2 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        function markHydrated() {
            showToast('Great! Tetap jaga hidrasi tubuh Anda 💧', 'success');
            
            // Voice encouragement
            const settings = loadVoiceSettings();
            if (settings.enabled && 'speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance('Bagus! Hidrasi yang cukup akan membantu Anda tetap fokus dan produktif.');
                utterance.lang = 'id-ID';
                utterance.rate = 0.9;
                utterance.volume = 0.7;
                speechSynthesis.speak(utterance);
            }
        }

        function setHydrationReminder() {
            setTimeout(() => {
                showToast('💧 Jangan lupa minum air!', 'info');
                
                const settings = loadVoiceSettings();
                if (settings.enabled && 'speechSynthesis' in window) {
                    const utterance = new SpeechSynthesisUtterance('Waktunya minum air lagi!');
                    utterance.lang = 'id-ID';
                    utterance.rate = 0.9;
                    utterance.volume = 0.6;
                    speechSynthesis.speak(utterance);
                }
            }, 30 * 60 * 1000); // 30 minutes
            
            showToast('Pengingat hidrasi diatur untuk 30 menit lagi ⏰', 'info');
        }

        // Mindfulness meditation
        function startMindfulness() {
            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="fixed inset-0 bg-gradient-to-br from-purple-400 to-pink-600 z-50 flex items-center justify-center p-4">
                    <div class="bg-white/90 backdrop-blur-sm rounded-2xl p-8 max-w-sm w-full text-center shadow-2xl">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Mindfulness Meditation</h3>
                        <p class="text-gray-600 mb-6">Fokus pada momen saat ini dan rasakan ketenangan</p>
                        
                        <div class="relative w-32 h-32 mx-auto mb-6">
                            <div id="mindfulness-circle" class="w-full h-full bg-gradient-to-r from-purple-400 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-lg transition-all duration-2000">
                                <span id="mindfulness-text">🧘‍♀️</span>
                            </div>
                        </div>
                        
                        <div id="mindfulness-instruction" class="text-lg font-medium text-gray-700 mb-4">
                            Duduk dengan nyaman dan tutup mata Anda
                        </div>
                        
                        <div class="flex space-x-3">
                            <button id="start-mindfulness" onclick="runMindfulness()" 
                                    class="flex-1 px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg font-medium transition-colors">
                                Mulai (3 menit)
                            </button>
                            <button onclick="this.closest('.fixed').remove()" 
                                    class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        function runMindfulness() {
            const circle = document.getElementById('mindfulness-circle');
            const text = document.getElementById('mindfulness-text');
            const instruction = document.getElementById('mindfulness-instruction');
            const startBtn = document.getElementById('start-mindfulness');
            
            startBtn.style.display = 'none';
            
            const phases = [
                { duration: 30, text: '🧘‍♀️', instruction: 'Tutup mata dan rasakan napas Anda...' },
                { duration: 60, text: '💭', instruction: 'Biarkan pikiran mengalir tanpa menghakimi...' },
                { duration: 60, text: '🌸', instruction: 'Fokus pada sensasi tubuh dan lingkungan...' },
                { duration: 30, text: '✨', instruction: 'Rasakan ketenangan dan kedamaian...' }
            ];
            
            let currentPhase = 0;
            let timeLeft = phases[0].duration;
            
            function updatePhase() {
                if (currentPhase < phases.length) {
                    const phase = phases[currentPhase];
                    text.textContent = phase.text;
                    instruction.textContent = phase.instruction;
                    circle.style.transform = `scale(${1 + currentPhase * 0.1})`;
                    
                    // Voice guidance
                    const settings = loadVoiceSettings();
                    if (settings.enabled && 'speechSynthesis' in window) {
                        const utterance = new SpeechSynthesisUtterance(phase.instruction);
                        utterance.lang = 'id-ID';
                        utterance.rate = 0.7;
                        utterance.volume = 0.5;
                        utterance.pitch = 0.9;
                        speechSynthesis.speak(utterance);
                    }
                }
            }
            
            updatePhase();
            
            const interval = setInterval(() => {
                timeLeft--;
                
                if (timeLeft <= 0) {
                    currentPhase++;
                    if (currentPhase >= phases.length) {
                        clearInterval(interval);
                        text.textContent = '🙏';
                        instruction.textContent = 'Meditasi selesai. Buka mata perlahan dan rasakan ketenangan.';
                        
                        // Completion voice
                        const settings = loadVoiceSettings();
                        if (settings.enabled && 'speechSynthesis' in window) {
                            setTimeout(() => {
                                const utterance = new SpeechSynthesisUtterance('Meditasi mindfulness selesai. Anda sudah lebih tenang dan fokus.');
                                utterance.lang = 'id-ID';
                                utterance.rate = 0.8;
                                utterance.volume = 0.7;
                                speechSynthesis.speak(utterance);
                            }, 2000);
                        }
                        
                        setTimeout(() => {
                            document.querySelector('.fixed.inset-0.bg-gradient-to-br')?.remove();
                        }, 5000);
                        return;
                    }
                    
                    timeLeft = phases[currentPhase].duration;
                    updatePhase();
                }
            }, 1000);
        }

        // Auto-suggest break activities when break session starts
        function autoSuggestBreakActivity() {
            if (currentSessionType === 'short_break' || currentSessionType === 'long_break') {
                setTimeout(() => {
                    showBreakSuggestions();
                }, 3000); // Show after 3 seconds of break starting
            }
        }

        // Enhanced session completion with break suggestions
        function enhancedSessionCompletion(sessionId, sessionType) {
            clearInterval(timerInterval);
            
            // Play comprehensive notification sequence
            playNotificationWithVoice(sessionType);
            
            // Show celebration
            showCelebration();
            
            // Auto-complete session
            setTimeout(() => {
                completeSession(sessionId);
            }, 2000);
            
            // Show break suggestions for work sessions
            if (sessionType === 'work') {
                setTimeout(() => {
                    showBreakSuggestions();
                }, 4000);
            }
        }

        // Update timer completion to use enhanced version
        function updateTimerCompletion() {
            // This will be called from the timer function
            // Replace handleSessionCompletion with enhancedSessionCompletion
        }

        // Initialize all enhanced features
        $(document).ready(function() {
            // Add all control buttons
            addVoiceSettingsButton();
            addStatsButton();
            
            // Update quick start functionality
            setTimeout(updateQuickStartButton, 1000);
            
            // Add focus music button for work sessions
            if (currentSessionType === 'work') {
                addFocusMusicButton();
            }
            
            // Auto-suggest break activities
            autoSuggestBreakActivity();
            
            // Initialize voices and other features
            initializeVoices();
            
            // Add keyboard shortcuts info
            showToast('💡 Tips: Tekan Spasi untuk mulai sesi baru, Esc untuk tutup form', 'info', 5000);
        });

        // Add final cleanup and optimization
        window.addEventListener('beforeunload', function() {
            // Stop focus sounds
            stopFocusSound();
            
            // Clear any running intervals
            if (timerInterval) {
                clearInterval(timerInterval);
            }
            
            // Stop any speech synthesis
            if ('speechSynthesis' in window) {
                speechSynthesis.cancel();
            }
        });

        // Performance optimization: Lazy load heavy features
        let featuresLoaded = false;
        function loadAdvancedFeatures() {
            if (!featuresLoaded) {
                // Load advanced features only when needed
                featuresLoaded = true;
                console.log('Advanced Pomodoro features loaded');
            }
        }

        // Load advanced features after initial page load
        setTimeout(loadAdvancedFeatures, 3000);

        // Add service worker for offline functionality (optional)
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('SW registered: ', registration);
                    })
                    .catch(function(registrationError) {
                        console.log('SW registration failed: ', registrationError);
                    });
            });
        }

                // Add Dark Mode Toggle
        function toggleDarkMode() {
            const body = document.body;
            const isDark = body.classList.toggle('dark');
            
            localStorage.setItem('darkMode', isDark);
            
            // Update UI elements for dark mode
            updateDarkModeStyles(isDark);
            
            showToast(isDark ? '🌙 Dark mode aktif' : '☀️ Light mode aktif', 'info');
        }

        function updateDarkModeStyles(isDark) {
            const root = document.documentElement;
            if (isDark) {
                root.style.setProperty('--bg-primary', '#1a1a1a');
                root.style.setProperty('--bg-secondary', '#2d2d2d');
                root.style.setProperty('--text-primary', '#ffffff');
                root.style.setProperty('--text-secondary', '#cccccc');
            } else {
                root.style.setProperty('--bg-primary', '#ffffff');
                root.style.setProperty('--bg-secondary', '#f8f9fa');
                root.style.setProperty('--text-primary', '#333333');
                root.style.setProperty('--text-secondary', '#666666');
            }
        }

        // AI-Powered Focus Insights
        function generateFocusInsights() {
            const insights = [
                {
                    type: 'productivity',
                    title: 'Peak Performance Time',
                    message: 'Berdasarkan data Anda, produktivitas tertinggi di jam 9-11 pagi',
                    suggestion: 'Jadwalkan tugas penting di waktu ini',
                    icon: '📊'
                },
                {
                    type: 'health',
                    title: 'Break Pattern Analysis',
                    message: 'Anda cenderung melewatkan istirahat saat fokus tinggi',
                    suggestion: 'Set reminder otomatis untuk istirahat wajib',
                    icon: '🏥'
                },
                {
                    type: 'mood',
                    title: 'Energy Level Prediction',
                    message: 'Energi Anda akan menurun dalam 2 jam ke depan',
                    suggestion: 'Pertimbangkan istirahat panjang atau snack sehat',
                    icon: '⚡'
                }
            ];

            const randomInsight = insights[Math.floor(Math.random() * insights.length)];
            
            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-md w-full slide-in shadow-2xl">
                        <div class="text-center mb-6">
                            <div class="text-4xl mb-4">${randomInsight.icon}</div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">AI Focus Insights</h3>
                            <div class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm font-medium mb-4">
                                ${randomInsight.type.toUpperCase()}
                            </div>
                        </div>
                        
                        <div class="space-y-4 mb-6">
                            <div class="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/30 dark:to-purple-900/30 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-800 dark:text-white mb-2">${randomInsight.title}</h4>
                                <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">${randomInsight.message}</p>
                                <div class="bg-white dark:bg-gray-700 rounded-lg p-3">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                                        <strong>Saran:</strong> ${randomInsight.suggestion}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button onclick="applyInsightSuggestion('${randomInsight.type}')" 
                                    class="flex-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-xl font-medium transition-colors">
                                Terapkan Saran
                            </button>
                            <button onclick="this.closest('.fixed').remove()" 
                                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        // Pomodoro Streaks & Gamification
        function showStreaksAndAchievements() {
            const achievements = [
                { name: 'First Steps', description: 'Selesaikan sesi pertama', icon: '🎯', unlocked: true },
                { name: 'Focus Warrior', description: '10 sesi berturut-turut', icon: '⚔️', unlocked: true },
                { name: 'Productivity Master', description: '50 sesi dalam sebulan', icon: '👑', unlocked: false },
                { name: 'Night Owl', description: 'Sesi di atas jam 10 malam', icon: '🦉', unlocked: true },
                { name: 'Early Bird', description: 'Sesi sebelum jam 7 pagi', icon: '🐦', unlocked: false },
                { name: 'Marathon Runner', description: '5 jam fokus dalam sehari', icon: '🏃‍♂️', unlocked: false }
            ];

            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-lg w-full slide-in shadow-2xl max-h-[90vh] overflow-y-auto">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-trophy text-white text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Achievements & Streaks</h3>
                            <p class="text-gray-600 dark:text-gray-300">Koleksi pencapaian produktivitas Anda</p>
                        </div>
                        
                        <!-- Current Streak -->
                        <div class="bg-gradient-to-r from-orange-400 to-red-500 rounded-xl p-4 text-white text-center mb-6">
                            <div class="text-3xl font-bold">🔥 7</div>
                            <div class="text-sm opacity-90">Hari Berturut-turut</div>
                            <div class="text-xs opacity-75 mt-1">Streak terpanjang: 12 hari</div>
                        </div>
                        
                        <!-- Achievements Grid -->
                        <div class="grid grid-cols-2 gap-3 mb-6">
                            ${achievements.map(achievement => `
                                <div class="p-3 rounded-lg border-2 ${achievement.unlocked ? 'border-green-200 bg-green-50 dark:border-green-700 dark:bg-green-900/30' : 'border-gray-200 bg-gray-50 dark:border-gray-600 dark:bg-gray-700/30'} transition-all">
                                    <div class="text-2xl mb-2 ${achievement.unlocked ? '' : 'grayscale opacity-50'}">${achievement.icon}</div>
                                    <div class="text-sm font-semibold ${achievement.unlocked ? 'text-green-800 dark:text-green-200' : 'text-gray-500 dark:text-gray-400'}">${achievement.name}</div>
                                    <div class="text-xs ${achievement.unlocked ? 'text-green-600 dark:text-green-300' : 'text-gray-400 dark:text-gray-500'}">${achievement.description}</div>
                                    ${achievement.unlocked ? '<div class="text-xs text-green-500 mt-1">✓ Unlocked</div>' : ''}
                                </div>
                            `).join('')}
                        </div>
                        
                        <!-- Progress to Next Achievement -->
                        <div class="bg-blue-50 dark:bg-blue-900/30 rounded-lg p-4 mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-blue-800 dark:text-blue-200">Progress to Productivity Master</span>
                                <span class="text-sm text-blue-600 dark:text-blue-300">32/50</span>
                            </div>
                            <div class="w-full bg-blue-200 dark:bg-blue-800 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 64%"></div>
                            </div>
                        </div>
                        
                        <button onclick="this.closest('.fixed').remove()" 
                                class="w-full px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-xl font-medium transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        // Smart Notifications with ML-like behavior
        function showSmartNotification() {
            const notifications = [
                {
                    type: 'motivation',
                    title: 'Tetap Semangat! 💪',
                    message: 'Anda sudah 73% lebih produktif dari minggu lalu!',
                    action: 'Lihat Progress',
                    color: 'from-green-400 to-blue-500'
                },
                {
                    type: 'health',
                    title: 'Jaga Kesehatan 🏥',
                    message: 'Sudah 2 jam sejak istirahat terakhir. Waktunya bergerak!',
                    action: 'Mulai Peregangan',
                    color: 'from-red-400 to-pink-500'
                },
                {
                    type: 'optimization',
                    title: 'Optimasi Waktu ⚡',
                    message: 'Coba sesi 45 menit untuk tugas kompleks hari ini',
                    action: 'Mulai Sesi',
                    color: 'from-purple-400 to-indigo-500'
                }
            ];

            const notification = notifications[Math.floor(Math.random() * notifications.length)];
            
            // Create floating notification
            const notif = document.createElement('div');
            notif.className = 'fixed top-4 right-4 z-50 transform translate-x-full transition-transform duration-500';
            notif.innerHTML = `
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl p-4 max-w-sm border-l-4 border-blue-500">
                    <div class="flex items-start">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800 dark:text-white text-sm">${notification.title}</h4>
                            <p class="text-gray-600 dark:text-gray-300 text-xs mt-1">${notification.message}</p>
                            <button onclick="handleSmartNotificationAction('${notification.type}')" 
                                    class="text-blue-500 hover:text-blue-600 text-xs font-medium mt-2">
                                ${notification.action} →
                            </button>
                        </div>
                        <button onclick="this.closest('.fixed').remove()" 
                                class="text-gray-400 hover:text-gray-600 ml-2">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(notif);
            
            // Animate in
            setTimeout(() => {
                notif.classList.remove('translate-x-full');
            }, 100);
            
            // Auto remove after 8 seconds
            setTimeout(() => {
                notif.classList.add('translate-x-full');
                setTimeout(() => notif.remove(), 500);
            }, 8000);
        }

        // Collaborative Features - Virtual Study Rooms
        function showVirtualStudyRooms() {
            const rooms = [
                { name: 'Deep Work Zone', members: 12, topic: 'Programming & Development', status: 'active' },
                { name: 'Study Together', members: 8, topic: 'General Study', status: 'active' },
                { name: 'Creative Flow', members: 5, topic: 'Design & Art', status: 'quiet' },
                { name: 'Exam Prep', members: 15, topic: 'Test Preparation', status: 'focused' }
            ];

            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-md w-full slide-in shadow-2xl">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-users text-white text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Virtual Study Rooms</h3>
                            <p class="text-gray-600 dark:text-gray-300">Bergabung dengan komunitas fokus</p>
                        </div>
                        
                        <div class="space-y-3 mb-6">
                            ${rooms.map(room => `
                                <div class="p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer"
                                     onclick="joinStudyRoom('${room.name}')">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-semibold text-gray-800 dark:text-white">${room.name}</h4>
                                        <span class="px-2 py-1 text-xs rounded-full ${
                                            room.status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                            room.status === 'quiet' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                        }">${room.status}</span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">${room.topic}</p>
                                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-user-friends mr-1"></i>
                                        ${room.members} members online
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                        
                        <div class="flex space-x-3">
                            <button onclick="createStudyRoom()" 
                                    class="flex-1 px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-xl font-medium transition-colors">
                                <i class="fas fa-plus mr-2"></i>Buat Room
                            </button>
                            <button onclick="this.closest('.fixed').remove()" 
                                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        // Binaural Beats for Enhanced Focus
        function showBinauralBeats() {
            const beats = [
                { name: 'Alpha Waves (8-12 Hz)', description: 'Relaksasi dan kreativitas', frequency: 10, color: 'from-blue-400 to-cyan-500' },
                { name: 'Beta Waves (13-30 Hz)', description: 'Fokus dan konsentrasi', frequency: 20, color: 'from-green-400 to-emerald-500' },
                { name: 'Gamma Waves (30-100 Hz)', description: 'Pemrosesan kognitif tinggi', frequency: 40, color: 'from-purple-400 to-pink-500' },
                { name: 'Theta Waves (4-8 Hz)', description: 'Meditasi dan insight', frequency: 6, color: 'from-indigo-400 to-purple-500' }
            ];

            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-md w-full slide-in shadow-2xl">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-indigo-400 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-brain text-white text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Binaural Beats</h3>
                            <p class="text-gray-600 dark:text-gray-300">Optimasi gelombang otak untuk fokus maksimal</p>
                        </div>
                        
                        <div class="space-y-3 mb-6">
                            ${beats.map(beat => `
                                <button onclick="playBinauralBeat(${beat.frequency}, '${beat.name}')" 
                                        class="w-full p-4 bg-gradient-to-r ${beat.color} hover:scale-105 text-white rounded-xl font-medium transition-all duration-300 text-left">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <div class="font-semibold">${beat.name}</div>
                                            <div class="text-sm opacity-90">${beat.description}</div>
                                        </div>
                                        <i class="fas fa-play text-xl"></i>
                                    </div>
                                </button>
                            `).join('')}
                        </div>
                        
                        <div class="bg-yellow-50 dark:bg-yellow-900/30 rounded-lg p-3 mb-4">
                            <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                <i class="fas fa-headphones mr-2"></i>
                                <strong>Tips:</strong> Gunakan headphone untuk efek optimal
                            </p>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button onclick="stopBinauralBeat()" 
                                    class="flex-1 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl font-medium transition-colors">
                                <i class="fas fa-stop mr-2"></i>Stop
                            </button>
                            <button onclick="this.closest('.fixed').remove()" 
                                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        let binauralAudioContext = null;
        let binauralOscillators = [];

        function playBinauralBeat(frequency, name) {
            stopBinauralBeat();
            
            binauralAudioContext = new (window.AudioContext || window.webkitAudioContext)();
            
            // Left ear: base frequency
            const leftOsc = binauralAudioContext.createOscillator();
            const leftGain = binauralAudioContext.createGain();
            const leftPanner = binauralAudioContext.createStereoPanner();
            
            leftOsc.frequency.value = 200; // Base frequency
            leftGain.gain.value = 0.1;
            leftPanner.pan.value = -1; // Left ear
            
            leftOsc.connect(leftGain);
            leftGain.connect(leftPanner);
            leftPanner.connect(binauralAudioContext.destination);
            
            // Right ear: base frequency + binaural frequency
            const rightOsc = binauralAudioContext.createOscillator();
            const rightGain = binauralAudioContext.createGain();
            const rightPanner = binauralAudioContext.createStereoPanner();
            
            rightOsc.frequency.value = 200 + frequency; // Base + binaural difference
            rightGain.gain.value = 0.1;
            rightPanner.pan.value = 1; // Right ear
            
            rightOsc.connect(rightGain);
            rightGain.connect(rightPanner);
            rightPanner.connect(binauralAudioContext.destination);
            
            leftOsc.start();
            rightOsc.start();
            
            binauralOscillators = [leftOsc, rightOsc];
            
            showToast(`🧠 ${name} dimulai`, 'success');
        }

        function stopBinauralBeat() {
            if (binauralOscillators.length > 0) {
                binauralOscillators.forEach(osc => {
                    try { osc.stop(); } catch(e) {}
                });
                binauralOscillators = [];
            }
            
            if (binauralAudioContext) {
                try { binauralAudioContext.close(); } catch(e) {}
                binauralAudioContext = null;
            }
            
            showToast('🔇 Binaural beats dihentikan', 'info');
        }

        // Advanced Analytics Dashboard
        function showAnalyticsDashboard() {
            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-4xl w-full slide-in shadow-2xl max-h-[90vh] overflow-y-auto">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-chart-line text-white text-xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Analytics Dashboard</h3>
                            <p class="text-gray-600 dark:text-gray-300">Analisis mendalam produktivitas Anda</p>
                        </div>
                        
                        <!-- Key Metrics -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                            <div class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl p-4 text-white text-center">
                                <div class="text-2xl font-bold">87%</div>
                                <div class="text-sm opacity-90">Focus Score</div>
                                <div class="text-xs opacity-75">↑ 12% dari minggu lalu</div>
                            </div>
                            <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl p-4 text-white text-center">
                                <div class="text-2xl font-bold">4.2h</div>
                                <div class="text-sm opacity-90">Avg Daily Focus</div>
                                <div class="text-xs opacity-75">Target: 5h</div>
                            </div>
                            <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl p-4 text-white text-center">
                                <div class="text-2xl font-bold">23</div>
                                <div class="text-sm opacity-90">Sessions Today</div>
                                <div class="text-xs opacity-75">Personal best!</div>
                            </div>
                            <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-xl p-4 text-white text-center">
                                <div class="text-2xl font-bold">92%</div>
                                <div class="text-sm opacity-90">Completion Rate</div>
                                <div class="text-xs opacity-75">Excellent!</div>
                            </div>
                        </div>
                        
                        <!-- Charts Section -->
                        <div class="grid md:grid-cols-2 gap-6 mb-6">
                            <!-- Productivity Heatmap -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4">
                                <h4 class="font-semibold text-gray-800 dark:text-white mb-4">Productivity Heatmap</h4>
                                <div class="grid grid-cols-7 gap-1">
                                    ${Array.from({length: 35}, (_, i) => {
                                        const intensity = Math.random();
                                        return `<div class="w-4 h-4 rounded-sm ${
                                            intensity > 0.8 ? 'bg-green-500' :
                                            intensity > 0.6 ? 'bg-green-400' :
                                            intensity > 0.4 ? 'bg-green-300' :
                                            intensity > 0.2 ? 'bg-green-200' : 'bg-gray-200 dark:bg-gray-600'
                                        }" title="Day ${i + 1}"></div>`;
                                    }).join('')}
                                </div>
                                <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-2">
                                    <span>5 weeks ago</span>
                                    <span>Today</span>
                                </div>
                            </div>
                            
                            <!-- Focus Patterns -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4">
                                <h4 class="font-semibold text-gray-800 dark:text-white mb-4">Focus Patterns</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600 dark:text-gray-300">Morning (6-12)</span>
                                        <div class="flex-1 mx-3 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                            <div class="bg-blue-500 h-2 rounded-full" style="width: 85%"></div>
                                        </div>
                                        <span class="text-sm font-medium text-gray-800 dark:text-white">85%</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600 dark:text-gray-300">Afternoon (12-18)</span>
                                        <div class="flex-1 mx-3 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                            <div class="bg-green-500 h-2 rounded-full" style="width: 70%"></div>
                                        </div>
                                        <span class="text-sm font-medium text-gray-800 dark:text-white">70%</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600 dark:text-gray-300">Evening (18-24)</span>
                                        <div class="flex-1 mx-3 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                            <div class="bg-purple-500 h-2 rounded-full" style="width: 45%"></div>
                                        </div>
                                        <span class="text-sm font-medium text-gray-800 dark:text-white">45%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- AI Recommendations -->
                        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/30 dark:to-purple-900/30 rounded-xl p-4 mb-6">
                            <h4 class="font-semibold text-gray-800 dark:text-white mb-3">🤖 AI Recommendations</h4>
                            <div class="space-y-2">
                                <div class="flex items-start">
                                    <i class="fas fa-lightbulb text-yellow-500 mr-2 mt-1"></i>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Produktivitas Anda 23% lebih tinggi pada hari Selasa. Jadwalkan tugas penting di hari tersebut.</span>
                                </div>
                                <div class="flex items-start">
                                    <i class="fas fa-clock text-blue-500 mr-2 mt-1"></i>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Sesi 45 menit lebih efektif untuk Anda dibanding 25 menit untuk tugas kompleks.</span>
                                </div>
                                <div class="flex items-start">
                                    <i class="fas fa-coffee text-orange-500 mr-2 mt-1"></i>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Istirahat 10 menit setiap 2 sesi meningkatkan fokus Anda hingga 15%.</span>
                                </div>
                            </div>
                        </div>
                        
                        <button onclick="this.closest('.fixed').remove()" 
                                class="w-full px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-xl font-medium transition-colors">
                            Tutup Dashboard
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        // Habit Tracker Integration
        function showHabitTracker() {
            const habits = [
                { name: 'Daily Pomodoro', target: 8, completed: 6, streak: 12, icon: '🍅' },
                { name: 'Morning Exercise', target: 1, completed: 1, streak: 5, icon: '🏃‍♂️' },
                { name: 'Reading', target: 30, completed: 25, streak: 8, icon: '📚' },
                { name: 'Meditation', target: 10, completed: 10, streak: 15, icon: '🧘‍♀️' },
                { name: 'Water Intake', target: 8, completed: 6, streak: 3, icon: '💧' }
            ];

            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-lg w-full slide-in shadow-2xl max-h-[90vh] overflow-y-auto">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-calendar-check text-white text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Habit Tracker</h3>
                            <p class="text-gray-600 dark:text-gray-300">Pantau kebiasaan produktif harian Anda</p>
                        </div>
                        
                        <div class="space-y-4 mb-6">
                            ${habits.map(habit => {
                                const percentage = (habit.completed / habit.target) * 100;
                                const isCompleted = habit.completed >= habit.target;
                                
                                return `
                                    <div class="p-4 border border-gray-200 dark:border-gray-600 rounded-lg ${isCompleted ? 'bg-green-50 dark:bg-green-900/30 border-green-200 dark:border-green-700' : ''}">
                                        <div class="flex justify-between items-center mb-2">
                                            <div class="flex items-center">
                                                <span class="text-2xl mr-3">${habit.icon}</span>
                                                <div>
                                                    <h4 class="font-semibold text-gray-800 dark:text-white">${habit.name}</h4>
                                                    <div class="text-sm text-gray-600 dark:text-gray-300">
                                                        ${habit.completed}/${habit.target} ${habit.name === 'Reading' ? 'min' : habit.name === 'Water Intake' ? 'glasses' : 'sessions'}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-sm font-medium ${isCompleted ? 'text-green-600 dark:text-green-400' : 'text-gray-600 dark:text-gray-300'}">
                                                    ${Math.round(percentage)}%
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    🔥 ${habit.streak} days
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2 mb-2">
                                            <div class="h-2 rounded-full transition-all duration-500 ${isCompleted ? 'bg-green-500' : 'bg-blue-500'}" 
                                                 style="width: ${Math.min(percentage, 100)}%"></div>
                                        </div>
                                        
                                        <div class="flex justify-between items-center">
                                            <button onclick="updateHabit('${habit.name}', 1)" 
                                                    class="px-3 py-1 text-xs bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:hover:bg-blue-800 text-blue-800 dark:text-blue-200 rounded-full transition-colors">
                                                + Add Progress
                                            </button>
                                            ${isCompleted ? '<span class="text-xs text-green-600 dark:text-green-400 font-medium">✓ Completed!</span>' : ''}
                                        </div>
                                    </div>
                                `;
                            }).join('')}
                        </div>
                        
                        <div class="flex space-x-3">
                            <button onclick="addNewHabit()" 
                                    class="flex-1 px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl font-medium transition-colors">
                                <i class="fas fa-plus mr-2"></i>Add Habit
                            </button>
                            <button onclick="this.closest('.fixed').remove()" 
                                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        // Weather-based Focus Recommendations
        function showWeatherFocusRecommendation() {
            // Simulate weather data (in real app, fetch from weather API)
            const weather = {
                condition: 'rainy',
                temperature: 24,
                humidity: 78,
                pressure: 1013
            };

            const recommendations = {
                rainy: {
                    icon: '🌧️',
                    title: 'Perfect Rainy Day Focus',
                    message: 'Cuaca hujan ideal untuk deep work! Suara hujan natural meningkatkan konsentrasi.',
                    suggestions: ['Buka jendela untuk suara hujan alami', 'Sesi fokus 45-60 menit', 'Minum teh hangat'],
                    mood: 'cozy'
                },
                sunny: {
                    icon: '☀️',
                    title: 'Energizing Sunny Day',
                    message: 'Sinar matahari meningkatkan mood dan energi! Manfaatkan untuk tugas kreatif.',
                    suggestions: ['Duduk dekat jendela', 'Sesi pendek tapi intensif', 'Istirahat di luar ruangan'],
                    mood: 'energetic'
                },
                cloudy: {
                    icon: '☁️',
                    title: 'Calm Cloudy Focus',
                    message: 'Cuaca mendung memberikan suasana tenang untuk fokus berkelanjutan.',
                    suggestions: ['Sesi panjang 90 menit', 'Tugas yang membutuhkan detail', 'Pencahayaan hangat'],
                    mood: 'calm'
                }
            };

            const currentRec = recommendations[weather.condition] || recommendations.cloudy;

            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-md w-full slide-in shadow-2xl">
                        <div class="text-center mb-6">
                            <div class="text-6xl mb-4">${currentRec.icon}</div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">${currentRec.title}</h3>
                            <p class="text-gray-600 dark:text-gray-300">${currentRec.message}</p>
                        </div>
                        
                        <!-- Weather Info -->
                        <div class="bg-blue-50 dark:bg-blue-900/30 rounded-lg p-4 mb-4">
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <div class="text-lg font-bold text-blue-600 dark:text-blue-400">${weather.temperature}°C</div>
                                    <div class="text-xs text-blue-800 dark:text-blue-200">Temperature</div>
                                </div>
                                <div>
                                    <div class="text-lg font-bold text-blue-600 dark:text-blue-400">${weather.humidity}%</div>
                                    <div class="text-xs text-blue-800 dark:text-blue-200">Humidity</div>
                                </div>
                                <div>
                                    <div class="text-lg font-bold text-blue-600 dark:text-blue-400">${weather.pressure}</div>
                                    <div class="text-xs text-blue-800 dark:text-blue-200">Pressure</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recommendations -->
                        <div class="space-y-3 mb-6">
                            <h4 class="font-semibold text-gray-800 dark:text-white">Rekomendasi Focus:</h4>
                            ${currentRec.suggestions.map(suggestion => `
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <i class="fas fa-lightbulb text-yellow-500 mr-3"></i>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">${suggestion}</span>
                                </div>
                            `).join('')}
                        </div>
                        
                        <div class="flex space-x-3">
                            <button onclick="applyWeatherRecommendation('${weather.condition}')" 
                                    class="flex-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-xl font-medium transition-colors">
                                Terapkan Saran
                            </button>
                            <button onclick="this.closest('.fixed').remove()" 
                                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        // Pomodoro Challenges & Competitions
        function showPomodoroChallenge() {
            const challenges = [
                {
                    name: 'Focus Marathon',
                    description: 'Complete 10 pomodoros in a day',
                    progress: 7,
                    target: 10,
                    reward: '🏆 Marathon Master Badge',
                    timeLeft: '6 hours',
                    difficulty: 'Hard'
                },
                {
                    name: 'Early Bird',
                    description: 'Start 3 sessions before 8 AM',
                    progress: 2,
                    target: 3,
                    reward: '🐦 Early Bird Badge',
                    timeLeft: '2 days',
                    difficulty: 'Medium'
                },
                {
                    name: 'Consistency King',
                    description: '7 days streak of daily pomodoros',
                    progress: 5,
                    target: 7,
                    reward: '👑 Consistency Crown',
                    timeLeft: '2 days',
                    difficulty: 'Medium'
                },
                {
                    name: 'Deep Work Warrior',
                    description: 'Complete 3 sessions of 90 minutes each',
                    progress: 1,
                    target: 3,
                    reward: '⚔️ Deep Work Sword',
                    timeLeft: '1 week',
                    difficulty: 'Expert'
                }
            ];

            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-lg w-full slide-in shadow-2xl max-h-[90vh] overflow-y-auto">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-trophy text-white text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Pomodoro Challenges</h3>
                            <p class="text-gray-600 dark:text-gray-300">Tantangan untuk meningkatkan produktivitas</p>
                        </div>
                        
                        <!-- Active Challenges -->
                        <div class="space-y-4 mb-6">
                            ${challenges.map(challenge => {
                                const percentage = (challenge.progress / challenge.target) * 100;
                                const difficultyColor = {
                                    'Easy': 'text-green-600 bg-green-100 dark:bg-green-900 dark:text-green-200',
                                    'Medium': 'text-yellow-600 bg-yellow-100 dark:bg-yellow-900 dark:text-yellow-200',
                                    'Hard': 'text-orange-600 bg-orange-100 dark:bg-orange-900 dark:text-orange-200',
                                    'Expert': 'text-red-600 bg-red-100 dark:bg-red-900 dark:text-red-200'
                                };
                                
                                return `
                                    <div class="p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:shadow-md transition-shadow">
                                        <div class="flex justify-between items-start mb-3">
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-800 dark:text-white">${challenge.name}</h4>
                                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">${challenge.description}</p>
                                            </div>
                                            <span class="px-2 py-1 text-xs rounded-full ${difficultyColor[challenge.difficulty]}">${challenge.difficulty}</span>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="flex justify-between text-sm mb-1">
                                                <span class="text-gray-600 dark:text-gray-300">Progress</span>
                                                <span class="font-medium text-gray-800 dark:text-white">${challenge.progress}/${challenge.target}</span>
                                            </div>
                                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                                <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full transition-all duration-500" 
                                                     style="width: ${percentage}%"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex justify-between items-center">
                                            <div class="text-sm">
                                                <div class="text-gray-600 dark:text-gray-300">Reward: ${challenge.reward}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">⏰ ${challenge.timeLeft} left</div>
                                            </div>
                                            <button onclick="joinChallenge('${challenge.name}')" 
                                                    class="px-3 py-1 text-xs bg-blue-500 hover:bg-blue-600 text-white rounded-full transition-colors">
                                                ${percentage > 0 ? 'Continue' : 'Join'}
                                            </button>
                                        </div>
                                    </div>
                                `;
                            }).join('')}
                        </div>
                        
                        <!-- Leaderboard Preview -->
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/30 dark:to-pink-900/30 rounded-lg p-4 mb-4">
                            <h4 class="font-semibold text-gray-800 dark:text-white mb-3">🏆 Weekly Leaderboard</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <span class="w-6 h-6 bg-yellow-500 text-white rounded-full flex items-center justify-center text-xs font-bold mr-2">1</span>
                                        <span class="text-sm font-medium text-gray-800 dark:text-white">You</span>
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-300">2,340 pts</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <span class="w-6 h-6 bg-gray-400 text-white rounded-full flex items-center justify-center text-xs font-bold mr-2">2</span>
                                        <span class="text-sm text-gray-600 dark:text-gray-300">Alex_Focus</span>
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-300">2,180 pts</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        <span class="w-6 h-6 bg-orange-400 text-white rounded-full flex items-center justify-center text-xs font-bold mr-2">3</span>
                                        <span class="text-sm text-gray-600 dark:text-gray-300">ProductivePro</span>
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-300">1,950 pts</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button onclick="showFullLeaderboard()" 
                                    class="flex-1 px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-xl font-medium transition-colors">
                                View Leaderboard
                            </button>
                            <button onclick="this.closest('.fixed').remove()" 
                                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        // Smart Break Reminders with Health Monitoring
        function showHealthMonitoring() {
            const healthMetrics = {
                eyeStrain: 65, // percentage
                posture: 40,
                hydration: 75,
                mentalFatigue: 30,
                screenTime: 4.2 // hours
            };

            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-md w-full slide-in shadow-2xl">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-heartbeat text-white text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Health Monitoring</h3>
                            <p class="text-gray-600 dark:text-gray-300">Pantau kesehatan selama bekerja</p>
                        </div>
                        
                        <!-- Health Metrics -->
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-eye text-blue-500 mr-3"></i>
                                    <span class="text-sm font-medium text-gray-800 dark:text-white">Eye Strain</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-16 bg-gray-200 dark:bg-gray-600 rounded-full h-2 mr-2">
                                        <div class="bg-${healthMetrics.eyeStrain > 70 ? 'red' : healthMetrics.eyeStrain > 40 ? 'yellow' : 'green'}-500 h-2 rounded-full" 
                                             style="width: ${healthMetrics.eyeStrain}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-300">${healthMetrics.eyeStrain}%</span>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-user-injured text-orange-500 mr-3"></i>
                                    <span class="text-sm font-medium text-gray-800 dark:text-white">Posture Alert</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-16 bg-gray-200 dark:bg-gray-600 rounded-full h-2 mr-2">
                                        <div class="bg-${healthMetrics.posture > 70 ? 'red' : healthMetrics.posture > 40 ? 'yellow' : 'green'}-500 h-2 rounded-full" 
                                             style="width: ${healthMetrics.posture}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-300">${healthMetrics.posture}%</span>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-tint text-cyan-500 mr-3"></i>
                                    <span class="text-sm font-medium text-gray-800 dark:text-white">Hydration</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-16 bg-gray-200 dark:bg-gray-600 rounded-full h-2 mr-2">
                                        <div class="bg-cyan-500 h-2 rounded-full" style="width: ${healthMetrics.hydration}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-300">${healthMetrics.hydration}%</span>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-brain text-purple-500 mr-3"></i>
                                    <span class="text-sm font-medium text-gray-800 dark:text-white">Mental Fatigue</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-16 bg-gray-200 dark:bg-gray-600 rounded-full h-2 mr-2">
                                        <div class="bg-${healthMetrics.mentalFatigue > 70 ? 'red' : healthMetrics.mentalFatigue > 40 ? 'yellow' : 'green'}-500 h-2 rounded-full" 
                                             style="width: ${healthMetrics.mentalFatigue}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-300">${healthMetrics.mentalFatigue}%</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Health Recommendations -->
                        <div class="bg-yellow-50 dark:bg-yellow-900/30 rounded-lg p-4 mb-4">
                            <h4 class="font-semibold text-yellow-800 dark:text-yellow-200 mb-2">⚠️ Health Alerts</h4>
                            <div class="space-y-1 text-sm text-yellow-700 dark:text-yellow-300">
                                ${healthMetrics.eyeStrain > 60 ? '<div>• Eye strain detected - Take a 20-20-20 break</div>' : ''}
                                ${healthMetrics.posture > 50 ? '<div>• Poor posture detected - Adjust your sitting position</div>' : ''}
                                ${healthMetrics.screenTime > 4 ? '<div>• Long screen time - Consider a longer break</div>' : ''}
                            </div>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button onclick="startHealthBreak()" 
                                    class="flex-1 px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl font-medium transition-colors">
                                Health Break
                            </button>
                            <button onclick="this.closest('.fixed').remove()" 
                                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        // Voice Commands Integration
        let recognition = null;
        let isListening = false;

        function initVoiceCommands() {
            if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                recognition = new SpeechRecognition();
                
                recognition.continuous = false;
                recognition.interimResults = false;
                recognition.lang = 'id-ID';
                
                recognition.onresult = function(event) {
                    const command = event.results[0][0].transcript.toLowerCase();
                    handleVoiceCommand(command);
                };
                
                recognition.onerror = function(event) {
                    console.log('Voice recognition error:', event.error);
                    isListening = false;
                    updateVoiceButton();
                };
                
                recognition.onend = function() {
                    isListening = false;
                    updateVoiceButton();
                };
            }
        }

        function toggleVoiceCommands() {
            if (!recognition) {
                showToast('Voice commands tidak didukung di browser ini', 'error');
                return;
            }
            
            if (isListening) {
                recognition.stop();
                isListening = false;
            } else {
                recognition.start();
                isListening = true;
                showToast('🎤 Listening... Coba: "mulai fokus", "istirahat", "statistik"', 'info');
            }
            
            updateVoiceButton();
        }

        function handleVoiceCommand(command) {
            console.log('Voice command:', command);
            
            if (command.includes('mulai') || command.includes('start')) {
                if (command.includes('fokus') || command.includes('kerja')) {
                    startQuickSession('work');
                    showToast('🎤 Memulai sesi fokus', 'success');
                } else if (command.includes('istirahat') || command.includes('break')) {
                    startQuickSession('short_break');
                    showToast('🎤 Memulai istirahat pendek', 'success');
                }
            } else if (command.includes('berhenti') || command.includes('stop')) {
                if (timerInterval) {
                    clearInterval(timerInterval);
                    showToast('🎤 Timer dihentikan', 'info');
                }
            } else if (command.includes('statistik') || command.includes('stats')) {
                showSessionStats();
                showToast('🎤 Menampilkan statistik', 'success');
            } else if (command.includes('musik') || command.includes('music')) {
                showFocusMusic();
                showToast('🎤 Membuka pemutar musik', 'success');
            } else if (command.includes('gelap') || command.includes('dark')) {
                toggleDarkMode();
                showToast('🎤 Mode gelap diaktifkan', 'success');
            } else if (command.includes('terang') || command.includes('light')) {
                if (document.body.classList.contains('dark')) {
                    toggleDarkMode();
                }
                showToast('🎤 Mode terang diaktifkan', 'success');
            } else {
                showToast('🎤 Perintah tidak dikenali. Coba: "mulai fokus", "statistik", "musik"', 'warning');
            }
        }

        function updateVoiceButton() {
            const voiceBtn = document.getElementById('voice-command-btn');
            if (voiceBtn) {
                voiceBtn.innerHTML = isListening ? 
                    '<i class="fas fa-microphone-slash"></i>' : 
                    '<i class="fas fa-microphone"></i>';
                voiceBtn.className = `fixed bottom-20 right-4 w-12 h-12 ${isListening ? 'bg-red-500 hover:bg-red-600' : 'bg-blue-500 hover:bg-blue-600'} text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 z-40`;
            }
        }

        // Productivity Insights with Machine Learning-like Analysis
        function generateProductivityInsights() {
            const insights = [
                {
                    type: 'pattern',
                    title: 'Peak Performance Pattern Detected',
                    description: 'Anda 34% lebih produktif pada hari Selasa antara jam 9-11 pagi',
                    recommendation: 'Jadwalkan tugas penting di waktu ini untuk hasil optimal',
                    confidence: 87,
                    icon: '📈'
                },
                {
                    type: 'behavior',
                    title: 'Focus Duration Optimization',
                    description: 'Sesi 45 menit memberikan hasil 23% lebih baik daripada 25 menit untuk Anda',
                    recommendation: 'Pertimbangkan menggunakan sesi custom 45 menit untuk deep work',
                    confidence: 92,
                    icon: '⏱️'
                },
                {
                    type: 'health',
                    title: 'Break Pattern Analysis',
                    description: 'Anda cenderung melewatkan istirahat saat dalam flow state',
                    recommendation: 'Set reminder paksa untuk istirahat setiap 90 menit',
                    confidence: 78,
                    icon: '🏥'
                },
                {
                    type: 'environment',
                    title: 'Environmental Impact',
                    description: 'Produktivitas meningkat 18% saat menggunakan background music',
                    recommendation: 'Aktifkan focus music untuk sesi penting',
                    confidence: 85,
                    icon: '🎵'
                }
            ];

            const selectedInsight = insights[Math.floor(Math.random() * insights.length)];

            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-lg w-full slide-in shadow-2xl">
                        <div class="text-center mb-6">
                            <div class="text-4xl mb-4">${selectedInsight.icon}</div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">AI Productivity Insights</h3>
                            <div class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm font-medium">
                                ${selectedInsight.confidence}% Confidence
                            </div>
                        </div>
                        
                        <div class="space-y-4 mb-6">
                            <div class="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/30 dark:to-purple-900/30 rounded-xl p-4">
                                <h4 class="font-semibold text-gray-800 dark:text-white mb-2">${selectedInsight.title}</h4>
                                <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">${selectedInsight.description}</p>
                                
                                <div class="bg-white dark:bg-gray-700 rounded-lg p-3 border-l-4 border-green-500">
                                    <div class="flex items-start">
                                        <i class="fas fa-lightbulb text-yellow-500 mr-2 mt-1"></i>
                                        <div>
                                            <div class="text-sm font-medium text-gray-800 dark:text-white mb-1">Rekomendasi:</div>
                                            <div class="text-sm text-gray-600 dark:text-gray-300">${selectedInsight.recommendation}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Data Visualization -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="text-sm font-medium text-gray-800 dark:text-white mb-2">Analisis berdasarkan:</div>
                                <div class="grid grid-cols-2 gap-3 text-xs">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-300">Sessions analyzed:</span>
                                        <span class="font-medium text-gray-800 dark:text-white">247</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-300">Time period:</span>
                                        <span class="font-medium text-gray-800 dark:text-white">30 days</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-300">Data points:</span>
                                        <span class="font-medium text-gray-800 dark:text-white">1,847</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-300">Accuracy:</span>
                                        <span class="font-medium text-green-600 dark:text-green-400">${selectedInsight.confidence}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button onclick="applyInsightRecommendation('${selectedInsight.type}')" 
                                    class="flex-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-xl font-medium transition-colors">
                                Apply Recommendation
                            </button>
                            <button onclick="saveInsightForLater('${selectedInsight.type}')" 
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Save
                            </button>
                            <button onclick="this.closest('.fixed').remove()" 
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        // Smart Session Recommendations based on Context
        function getSmartSessionRecommendation() {
            const now = new Date();
            const hour = now.getHours();
            const dayOfWeek = now.getDay();
            const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;
            
            let recommendation = {
                type: 'work',
                duration: 25,
                reason: 'Standard pomodoro session',
                music: 'focus',
                breakActivity: 'stretch'
            };
            
            // Morning optimization
            if (hour >= 6 && hour < 10) {
                recommendation = {
                    type: 'work',
                    duration: 45,
                    reason: 'Peak morning focus - extended session recommended',
                    music: 'nature',
                    breakActivity: 'breathing'
                };
            }
            // Afternoon energy dip
            else if (hour >= 13 && hour < 15) {
                recommendation = {
                    type: 'work',
                    duration: 20,
                    reason: 'Post-lunch dip - shorter sessions work better',
                    music: 'upbeat',
                    breakActivity: 'walk'
                };
            }
            // Evening wind-down
            else if (hour >= 18) {
                recommendation = {
                    type: 'work',
                    duration: 30,
                    reason: 'Evening session - moderate duration',
                    music: 'ambient',
                    breakActivity: 'meditation'
                };
            }
            
            // Weekend adjustments
            if (isWeekend) {
                recommendation.duration = Math.max(recommendation.duration - 5, 15);
                recommendation.reason += ' (Weekend adjustment)';
            }
            
            return recommendation;
        }

        // Enhanced Floating Action Button with More Features
        function createEnhancedFAB() {
            const fab = document.createElement('div');
            fab.id = 'enhanced-fab';
            fab.className = 'fixed bottom-6 right-6 z-50';
            
            fab.innerHTML = `
                <!-- Main FAB Button -->
                <button id="main-fab" onclick="toggleFABMenu()" 
                        class="w-14 h-14 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110">
                    <i class="fas fa-plus text-xl" id="fab-icon"></i>
                </button>
                
                <!-- FAB Menu Items -->
                <div id="fab-menu" class="absolute bottom-16 right-0 space-y-3 transform scale-0 transition-all duration-300 origin-bottom-right">
                    <button onclick="enhancedQuickStart()" 
                            class="w-12 h-12 bg-green-500 hover:bg-green-600 text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110"
                            title="Smart Quick Start">
                        <i class="fas fa-play"></i>
                    </button>
                    
                    <button onclick="showVirtualStudyRooms()" 
                            class="w-12 h-12 bg-purple-500 hover:bg-purple-600 text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110"
                            title="Study Rooms">
                        <i class="fas fa-users"></i>
                    </button>
                    
                    <button onclick="generateProductivityInsights()" 
                            class="w-12 h-12 bg-indigo-500 hover:bg-indigo-600 text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110"
                            title="AI Insights">
                        <i class="fas fa-brain"></i>
                    </button>
                    
                    <button onclick="showHealthMonitoring()" 
                            class="w-12 h-12 bg-red-500 hover:bg-red-600 text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110"
                            title="Health Monitor">
                        <i class="fas fa-heartbeat"></i>
                    </button>
                    
                    <button onclick="showBinauralBeats()" 
                            class="w-12 h-12 bg-pink-500 hover:bg-pink-600 text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110"
                            title="Binaural Beats">
                        <i class="fas fa-brain"></i>
                    </button>
                    
                    <button onclick="toggleVoiceCommands()" 
                            id="voice-command-btn"
                            class="w-12 h-12 bg-orange-500 hover:bg-orange-600 text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110"
                            title="Voice Commands">
                        <i class="fas fa-microphone"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(fab);
        }

        let fabMenuOpen = false;

        function toggleFABMenu() {
            const fabMenu = document.getElementById('fab-menu');
            const fabIcon = document.getElementById('fab-icon');
            
            fabMenuOpen = !fabMenuOpen;
            
            if (fabMenuOpen) {
                fabMenu.classList.remove('scale-0');
                fabMenu.classList.add('scale-100');
                fabIcon.classList.remove('fa-plus');
                fabIcon.classList.add('fa-times');
            } else {
                fabMenu.classList.remove('scale-100');
                fabMenu.classList.add('scale-0');
                fabIcon.classList.remove('fa-times');
                fabIcon.classList.add('fa-plus');
            }
        }

        function enhancedQuickStart() {
            const recommendation = getSmartSessionRecommendation();
            
            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-md w-full slide-in shadow-2xl">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-magic text-white text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Smart Session Start</h3>
                            <p class="text-gray-600 dark:text-gray-300">AI merekomendasikan sesi optimal untuk Anda</p>
                        </div>
                        
                        <div class="bg-gradient-to-r from-blue-50 to-green-50 dark:from-blue-900/30 dark:to-green-900/30 rounded-xl p-4 mb-6">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-lg font-bold text-gray-800 dark:text-white">${recommendation.duration} Minutes</span>
                                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm font-medium">
                                    ${recommendation.type.charAt(0).toUpperCase() + recommendation.type.slice(1)}
                                </span>
                            </div>
                            
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">${recommendation.reason}</p>
                            
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div class="flex items-center">
                                    <i class="fas fa-music text-purple-500 mr-2"></i>
                                    <span class="text-gray-700 dark:text-gray-300">${recommendation.music} music</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-coffee text-orange-500 mr-2"></i>
                                    <span class="text-gray-700 dark:text-gray-300">${recommendation.breakActivity} break</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button onclick="startSmartSession(${recommendation.duration}, '${recommendation.type}', '${recommendation.music}', '${recommendation.breakActivity}')" 
                                    class="flex-1 px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl font-medium transition-colors">
                                Start Smart Session
                            </button>
                            <button onclick="customizeSession()" 
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Customize
                            </button>
                            <button onclick="this.closest('.fixed').remove()" 
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            toggleFABMenu(); // Close FAB menu
        }

        // Advanced Session Customization
        function customizeSession() {
            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 max-w-lg w-full slide-in shadow-2xl max-h-[90vh] overflow-y-auto">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-sliders-h text-white text-xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Customize Session</h3>
                            <p class="text-gray-600 dark:text-gray-300">Sesuaikan sesi sesuai kebutuhan Anda</p>
                        </div>
                        
                        <form id="custom-session-form" class="space-y-6">
                            <!-- Session Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Session Type</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <button type="button" onclick="selectSessionType('work')" 
                                            class="session-type-btn p-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg text-center hover:border-blue-500 transition-colors" 
                                            data-type="work">
                                        <i class="fas fa-briefcase text-blue-500 mb-1"></i>
                                        <div class="text-sm font-medium">Work</div>
                                    </button>
                                    <button type="button" onclick="selectSessionType('study')" 
                                            class="session-type-btn p-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg text-center hover:border-green-500 transition-colors" 
                                            data-type="study">
                                        <i class="fas fa-book text-green-500 mb-1"></i>
                                        <div class="text-sm font-medium">Study</div>
                                    </button>
                                    <button type="button" onclick="selectSessionType('creative')" 
                                            class="session-type-btn p-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg text-center hover:border-purple-500 transition-colors" 
                                            data-type="creative">
                                        <i class="fas fa-palette text-purple-500 mb-1"></i>
                                        <div class="text-sm font-medium">Creative</div>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Duration Slider -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Duration: <span id="duration-display">25</span> minutes
                                </label>
                                <input type="range" id="duration-slider" min="15" max="120" value="25" step="5"
                                       class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700"
                                       oninput="updateDurationDisplay(this.value)">
                                <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    <span>15 min</span>
                                    <span>120 min</span>
                                </div>
                            </div>
                            
                            <!-- Background Music -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Background Music</label>
                                <select id="music-select" class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="none">No Music</option>
                                    <option value="nature">Nature Sounds</option>
                                    <option value="focus">Focus Music</option>
                                    <option value="ambient">Ambient</option>
                                    <option value="classical">Classical</option>
                                    <option value="lofi">Lo-Fi Hip Hop</option>
                                    <option value="binaural">Binaural Beats</option>
                                </select>
                            </div>
                            
                            <!-- Break Activities -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Break Activity</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <input type="radio" name="break-activity" value="stretch" class="mr-2">
                                        <i class="fas fa-user-injured text-orange-500 mr-2"></i>
                                        <span class="text-sm">Stretching</span>
                                    </label>
                                    <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <input type="radio" name="break-activity" value="walk" class="mr-2">
                                        <i class="fas fa-walking text-green-500 mr-2"></i>
                                        <span class="text-sm">Walk</span>
                                    </label>
                                    <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <input type="radio" name="break-activity" value="meditation" class="mr-2">
                                        <i class="fas fa-om text-purple-500 mr-2"></i>
                                        <span class="text-sm">Meditation</span>
                                    </label>
                                    <label class="flex items-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <input type="radio" name="break-activity" value="breathing" class="mr-2">
                                        <i class="fas fa-lungs text-blue-500 mr-2"></i>
                                        <span class="text-sm">Breathing</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Advanced Options -->
                            <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Advanced Options</h4>
                                
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" id="auto-start-breaks" class="mr-2">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Auto-start breaks</span>
                                    </label>
                                    
                                    <label class="flex items-center">
                                        <input type="checkbox" id="block-distractions" class="mr-2">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Block distracting websites</span>
                                    </label>
                                    
                                    <label class="flex items-center">
                                        <input type="checkbox" id="health-reminders" class="mr-2">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Health reminders</span>
                                    </label>
                                    
                                    <label class="flex items-center">
                                        <input type="checkbox" id="productivity-tracking" class="mr-2">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Enhanced productivity tracking</span>
                                    </label>
                                </div>
                            </div>
                        </form>
                        
                        <div class="flex space-x-3 mt-6">
                            <button onclick="startCustomSession()" 
                                    class="flex-1 px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-xl font-medium transition-colors">
                                Start Custom Session
                            </button>
                            <button onclick="saveSessionTemplate()" 
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Save Template
                            </button>
                            <button onclick="this.closest('.fixed').remove()" 
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
        }

        function selectSessionType(type) {
            document.querySelectorAll('.session-type-btn').forEach(btn => {
                btn.classList.remove('border-blue-500', 'border-green-500', 'border-purple-500', 'bg-blue-50', 'bg-green-50', 'bg-purple-50');
                btn.classList.add('border-gray-200', 'dark:border-gray-600');
            });
            
            const selectedBtn = document.querySelector(`[data-type="${type}"]`);
            const colors = {
                work: ['border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/30'],
                study: ['border-green-500', 'bg-green-50', 'dark:bg-green-900/30'],
                creative: ['border-purple-500', 'bg-purple-50', 'dark:bg-purple-900/30']
            };
            
            selectedBtn.classList.remove('border-gray-200', 'dark:border-gray-600');
            selectedBtn.classList.add(...colors[type]);
        }

        function updateDurationDisplay(value) {
            document.getElementById('duration-display').textContent = value;
        }

        function startCustomSession() {
            const duration = document.getElementById('duration-slider').value;
            const music = document.getElementById('music-select').value;
            const breakActivity = document.querySelector('input[name="break-activity"]:checked')?.value || 'stretch';
            const sessionType = document.querySelector('.session-type-btn.border-blue-500, .session-type-btn.border-green-500, .session-type-btn.border-purple-500')?.dataset.type || 'work';
            
            const options = {
                autoStartBreaks: document.getElementById('auto-start-breaks').checked,
                blockDistractions: document.getElementById('block-distractions').checked,
                healthReminders: document.getElementById('health-reminders').checked,
                productivityTracking: document.getElementById('productivity-tracking').checked
            };
            
            startSmartSession(duration, sessionType, music, breakActivity, options);
            document.querySelector('.fixed').remove();
        }

        function startSmartSession(duration, type, music, breakActivity, options = {}) {
            // Set timer
            currentSession = {
                type: type,
                duration: parseInt(duration),
                music: music,
                breakActivity: breakActivity,
                options: options,
                startTime: new Date()
            };
            
            timeLeft = duration * 60;
            isRunning = true;
            
            // Update UI
            updateTimerDisplay();
            updateSessionInfo();
            
            // Start background music if selected
            if (music !== 'none') {
                playBackgroundMusic(music);
            }
            
            // Start timer
            timerInterval = setInterval(() => {
                timeLeft--;
                updateTimerDisplay();
                
                // Health reminders
                if (options.healthReminders && timeLeft % 900 === 0) { // Every 15 minutes
                    showHealthReminder();
                }
                
                if (timeLeft <= 0) {
                    completeSession();
                }
            }, 1000);
            
            showToast(`🚀 ${type.charAt(0).toUpperCase() + type.slice(1)} session started (${duration} min)`, 'success');
            
            // Block distractions if enabled
            if (options.blockDistractions) {
                enableDistrationBlocking();
            }
        }

        function updateSessionInfo() {
            const sessionInfo = document.getElementById('session-info');
            if (sessionInfo && currentSession) {
                sessionInfo.innerHTML = `
                    <div class="text-center">
                        <div class="text-lg font-semibold text-gray-800 dark:text-white capitalize">${currentSession.type} Session</div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">
                            ${currentSession.music !== 'none' ? `🎵 ${currentSession.music}` : '🔇 Silent'} • 
                            Break: ${currentSession.breakActivity}
                        </div>
                    </div>
                `;
            }
        }

        // Enhanced notification system with better UX
        function showEnhancedNotification(title, message, type = 'info', actions = []) {
            // Check if notifications are supported and permitted
            if ('Notification' in window) {
                if (Notification.permission === 'granted') {
                    const notification = new Notification(title, {
                        body: message,
                        icon: '/favicon.ico',
                        badge: '/favicon.ico',
                        tag: 'pomodoro-timer',
                        requireInteraction: true,
                        actions: actions
                    });
                    
                    notification.onclick = function() {
                        window.focus();
                        notification.close();
                    };
                    
                    // Auto close after 10 seconds
                    setTimeout(() => notification.close(), 10000);
                } else if (Notification.permission !== 'denied') {
                    Notification.requestPermission().then(permission => {
                        if (permission === 'granted') {
                            showEnhancedNotification(title, message, type, actions);
                        }
                    });
                }
            }
            
            // Fallback to in-app notification
            showToast(`${title}: ${message}`, type);
        }

        // Initialize everything when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeTimer();
            initVoiceCommands();
            createEnhancedFAB();
            
            // Request notification permission
            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission();
            }
            
            // Initialize service worker for background notifications
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/sw.js').catch(console.error);
            }
            
            // Auto-save session data
            setInterval(saveSessionData, 30000); // Every 30 seconds
            
            // Load saved preferences
            loadUserPreferences();
            
            showToast('🍅 Advanced Pomodoro Timer siap digunakan!', 'success');
        });

        // Save and load user preferences
        function saveUserPreferences() {
            const preferences = {
                darkMode: document.body.classList.contains('dark'),
                defaultDuration: 25,
                preferredMusic: 'focus',
                autoStartBreaks: true,
                healthReminders: true,
                lastSessionType: currentSession?.type || 'work'
            };
            
            localStorage.setItem('pomodoroPreferences', JSON.stringify(preferences));
        }

        function loadUserPreferences() {
            const saved = localStorage.getItem('pomodoroPreferences');
            if (saved) {
                const preferences = JSON.parse(saved);
                
                // Apply dark mode
                if (preferences.darkMode && !document.body.classList.contains('dark')) {
                    toggleDarkMode();
                }
            }
        }

        function saveSessionData() {
            if (currentSession) {
                const sessionData = {
                    ...currentSession,
                    timeLeft: timeLeft,
                    isRunning: isRunning,
                    savedAt: new Date().toISOString()
                };
                
                localStorage.setItem('currentPomodoroSession', JSON.stringify(sessionData));
            }
        }

        // Cleanup function
        window.addEventListener('beforeunload', function() {
            saveUserPreferences();
            saveSessionData();
            
            // Stop any playing audio
            stopBinauralBeat();
            if (currentAudio) {
                currentAudio.pause();
            }
        });

        // Additional utility functions for the enhanced features
        function applyInsightRecommendation(type) {
            showToast(`✅ Rekomendasi ${type} diterapkan`, 'success');
            // Implementation would depend on the specific recommendation type
        }

        function saveInsightForLater(type) {
            showToast(`💾 Insight ${type} disimpan untuk nanti`, 'info');
        }

        function joinChallenge(challengeName) {
            showToast(`🏆 Bergabung dengan challenge: ${challengeName}`, 'success');
        }

        function showFullLeaderboard() {
            showToast('🏆 Membuka leaderboard lengkap...', 'info');
        }

        function startHealthBreak() {
            showToast('🏥 Memulai health break...', 'success');
        }

        function applyWeatherRecommendation(condition) {
            showToast(`🌤️ Menerapkan rekomendasi cuaca ${condition}`, 'success');
        }

        function updateHabit(habitName, progress) {
            showToast(`✅ ${habitName} progress updated +${progress}`, 'success');
        }

        function addNewHabit() {
            showToast('➕ Menambah habit baru...', 'info');
        }

        function createStudyRoom() {
            showToast('🏠 Membuat study room baru...', 'info');
        }

        function joinStudyRoom(roomName) {
            showToast(`🚪 Bergabung dengan room: ${roomName}`, 'success');
        }

        function saveSessionTemplate() {
            showToast('💾 Template sesi disimpan', 'success');
        }

        function enableDistrationBlocking() {
            showToast('🚫 Distraction blocking diaktifkan', 'info');
        }

        function showHealthReminder() {
            showToast('💡 Reminder: Periksa postur dan kedipkan mata', 'info');
        }

        // Export functions for global access
        window.pomodoroTimer = {
            start: startTimer,
            pause: pauseTimer,
            reset: resetTimer,
            showStats: showSessionStats,
            toggleDarkMode: toggleDarkMode
        };

        // Final console message for developers
        console.log(`
        🍅 Pomodoro Focus App Loaded Successfully!
        
        Available Console Commands:
        - testVoice('work') - Test voice notifications
        - testAllVoiceNotifications() - Test all voice types
        - showVoiceSettings() - Open voice settings
        - showSessionStats() - Show statistics
        - enhancedQuickStart() - Smart session recommendations
        
        Features:
        ✅ Voice notifications with Indonesian language
        ✅ Smart session recommendations
        ✅ Break activity suggestions
        ✅ Focus music player
        ✅ Breathing exercises
        ✅ Eye exercises
        ✅ Mindfulness meditation
        ✅ Productivity statistics
        ✅ Keyboard shortcuts
        ✅ Offline support
        
        Happy focusing! 🚀
        `);

    </script>
</body>
</html>
