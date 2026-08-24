<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class StatsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $year = $request->integer('year', Carbon::today()->year);
        $month = $request->integer('month', Carbon::today()->month);

        $current = Carbon::create($year, $month, 1);

        // Mood per hari untuk kalender bulan ini
        $moodByDate = $user->journals()
            ->whereYear('entry_date', $current->year)
            ->whereMonth('entry_date', $current->month)
            ->pluck('mood', 'entry_date')
            ->mapWithKeys(fn ($mood, $date) => [Carbon::parse($date)->toDateString() => (int) $mood]);

        // Distribusi mood bulan ini
        $distribution = $user->journals()
            ->whereYear('entry_date', $current->year)
            ->whereMonth('entry_date', $current->month)
            ->selectRaw('mood, count(*) as total')
            ->groupBy('mood')
            ->orderBy('mood')
            ->get();

        // Rata-rata mood 6 bulan terakhir
        $monthlyTrend = collect(range(0, 5))->map(function ($i) use ($user, $current) {
            $monthDate = $current->copy()->subMonths($i);

            $avg = $user->journals()
                ->whereYear('entry_date', $monthDate->year)
                ->whereMonth('entry_date', $monthDate->month)
                ->avg('mood');

            return [
                'label' => $monthDate->translatedFormat('M'),
                'value' => $avg ? round((float) $avg, 2) : null,
            ];
        })->reverse()->values();

        $totalThisMonth = $distribution->sum('total');
        $bestDay = Journal::MOODS[5];
        $mostFrequentMood = $distribution->sortByDesc('total')->first()?->mood;

        return view('stats.index', compact(
            'current', 'moodByDate', 'distribution', 'monthlyTrend',
            'totalThisMonth', 'mostFrequentMood'
        ));
    }
}
