<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::today();

        $quote = Quote::inRandomOrder()->first();

        $todayJournal = $user->journals()->whereDate('entry_date', $today)->first();

        $stats = [
            'total' => $user->journals()->count(),
            'this_month' => $user->journals()->whereMonth('entry_date', $today->month)->whereYear('entry_date', $today->year)->count(),
            'streak' => $this->calculateStreak($user->id),
            'gratitude_today' => $user->gratitudeItems()->whereDate('entry_date', $today)->count(),
        ];

        $avgMood = round((float) $user->journals()
            ->whereMonth('entry_date', $today->month)
            ->whereYear('entry_date', $today->year)
            ->avg('mood'), 1);

        $recentJournals = $user->journals()
            ->with('reflectionPrompt')
            ->latest('entry_date')
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'quote', 'todayJournal', 'stats', 'avgMood', 'recentJournals'
        ));
    }

    private function calculateStreak(int $userId): int
    {
        $dates = DB::table('journals')
            ->where('user_id', $userId)
            ->distinct()
            ->orderByDesc('entry_date')
            ->pluck('entry_date')
            ->map(fn ($d) => Carbon::parse($d)->toDateString());

        if ($dates->isEmpty()) {
            return 0;
        }

        $streak = 0;
        $cursor = Carbon::today();

        if ($dates->first() !== $cursor->toDateString()) {
            $cursor->subDay();
            if ($dates->first() !== $cursor->toDateString()) {
                return 0;
            }
        }

        foreach ($dates as $date) {
            if ($date === $cursor->toDateString()) {
                $streak++;
                $cursor->subDay();
            } else {
                break;
            }
        }

        return $streak;
    }
}
