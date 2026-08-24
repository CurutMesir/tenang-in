<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePinUnlocked
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->hasPin() && ! $request->session()->get('pin_unlocked')) {
            return redirect()->route('pin.lock');
        }

        return $next($request);
    }
}
