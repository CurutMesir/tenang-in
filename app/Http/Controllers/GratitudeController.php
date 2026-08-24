<?php

namespace App\Http\Controllers;

use App\Models\GratitudeItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class GratitudeController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->integer('month', Carbon::today()->month);
        $year = $request->integer('year', Carbon::today()->year);

        $items = auth()->user()->gratitudeItems()
            ->whereMonth('entry_date', $month)
            ->whereYear('entry_date', $year)
            ->orderByDesc('created_at')
            ->get()
            ->groupBy(fn ($item) => $item->entry_date->format('Y-m-d'));

        return view('gratitude.index', [
            'items' => $items,
            'month' => Carbon::create($year, $month, 1),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:255'],
            'entry_date' => ['nullable', 'date', 'before_or_equal:today'],
        ]);

        $request->user()->gratitudeItems()->create([
            'content' => $validated['content'],
            'entry_date' => $validated['entry_date'] ?? Carbon::today()->toDateString(),
        ]);

        return back()->with('success', 'Syukur tersimpan 🌱');
    }

    public function destroy(GratitudeItem $gratitudeItem)
    {
        abort_unless($gratitudeItem->user_id === auth()->id(), 403);
        $gratitudeItem->delete();

        return back()->with('success', 'Catatan syukur dihapus.');
    }
}
