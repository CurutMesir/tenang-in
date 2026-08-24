@if ($role === 'user')
    <div class="flex justify-end">
        <div class="max-w-[80%] bg-gradient-to-br from-violet-500 to-indigo-400 text-white rounded-2xl rounded-tr-md px-4 py-2.5 text-sm leading-relaxed whitespace-pre-wrap break-words shadow-md shadow-violet-200">{{ $text }}</div>
    </div>
@else
    <div class="flex items-start gap-2.5">
        <span class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-300 to-indigo-300 flex-shrink-0"></span>
        <div class="max-w-[80%] bg-white/90 border border-violet-100/70 rounded-2xl rounded-tl-md px-4 py-2.5 text-sm leading-relaxed text-slate-600 whitespace-pre-wrap break-words">{{ $text }}</div>
    </div>
@endif
