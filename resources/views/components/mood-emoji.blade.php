@props(['mood', 'size' => 'md'])

@php $data = \App\Models\Journal::MOODS[$mood] ?? \App\Models\Journal::MOODS[3]; @endphp

<span {{ $attributes->merge(['class' => match($size) {
    'lg' => 'text-3xl',
    'sm' => 'text-lg',
    default => 'text-2xl',
}]) }}>
    {{ $data['emoji'] }}
</span>
