<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Journal extends Model
{
    public const MOODS = [
        1 => ['emoji' => '😞', 'label' => 'Berat Sekali', 'color' => '#8b9dc3', 'bg' => 'bg-indigo-200', 'text' => 'text-indigo-700'],
        2 => ['emoji' => '🙁', 'label' => 'Kurang Baik', 'color' => '#93c5fd', 'bg' => 'bg-blue-200', 'text' => 'text-blue-700'],
        3 => ['emoji' => '😐', 'label' => 'Biasa Saja', 'color' => '#fcd34d', 'bg' => 'bg-amber-200', 'text' => 'text-amber-700'],
        4 => ['emoji' => '🙂', 'label' => 'Cukup Senang', 'color' => '#86efac', 'bg' => 'bg-green-200', 'text' => 'text-green-700'],
        5 => ['emoji' => '😄', 'label' => 'Sangat Bahagia', 'color' => '#6ee7b7', 'bg' => 'bg-emerald-300', 'text' => 'text-emerald-700'],
    ];

    protected $fillable = [
        'user_id',
        'reflection_prompt_id',
        'title',
        'content',
        'mood',
        'entry_date',
    ];

    protected $casts = [
        'entry_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reflectionPrompt(): BelongsTo
    {
        return $this->belongsTo(ReflectionPrompt::class);
    }

    public function moodData(): array
    {
        return self::MOODS[$this->mood] ?? self::MOODS[3];
    }
}
