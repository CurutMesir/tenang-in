<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\ReflectionPrompt;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->journals()->with('reflectionPrompt');

        if ($request->filled('mood')) {
            $query->where('mood', $request->integer('mood'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $journals = $query->latest('entry_date')->latest('created_at')->paginate(9)->withQueryString();
        $moodCounts = auth()->user()->journals()
            ->selectRaw('mood, count(*) as total')
            ->groupBy('mood')
            ->pluck('total', 'mood');

        return view('journals.index', compact('journals', 'moodCounts'));
    }

    public function create()
    {
        $prompts = ReflectionPrompt::inRandomOrder()->take(3)->get();
        $today = Carbon::today();

        $existingToday = auth()->user()->journals()->whereDate('entry_date', $today)->exists();

        return view('journals.create', [
            'prompts' => $prompts,
            'today' => $today,
            'alreadyWritten' => $existingToday,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:150'],
            'content' => ['required', 'string', 'min:5'],
            'mood' => ['required', 'integer', 'between:1,5'],
            'entry_date' => ['required', 'date', 'before_or_equal:today'],
            'reflection_prompt_id' => ['nullable', 'exists:reflection_prompts,id'],
        ]);

        $request->user()->journals()->create($validated);

        return redirect()->route('dashboard')
            ->with('success', 'Jurnal berhasil disimpan. Terima kasih sudah jujur pada dirimu sendiri hari ini 🌸');
    }

    public function show(Journal $journal)
    {
        $this->authorizeAccess($journal);
        $journal->load('reflectionPrompt');

        return view('journals.show', compact('journal'));
    }

    public function edit(Journal $journal)
    {
        $this->authorizeAccess($journal);

        return view('journals.edit', compact('journal'));
    }

    public function update(Request $request, Journal $journal)
    {
        $this->authorizeAccess($journal);

        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:150'],
            'content' => ['required', 'string', 'min:5'],
            'mood' => ['required', 'integer', 'between:1,5'],
            'entry_date' => ['required', 'date', 'before_or_equal:today'],
        ]);

        $journal->update($validated);

        return redirect()->route('journals.show', $journal)
            ->with('success', 'Jurnal berhasil diperbarui ✨');
    }

    public function destroy(Journal $journal)
    {
        $this->authorizeAccess($journal);
        $journal->delete();

        return redirect()->route('journals.index')
            ->with('success', 'Jurnal telah dihapus.');
    }

    private function authorizeAccess(Journal $journal): void
    {
        abort_unless($journal->user_id === auth()->id(), 403);
    }
}
