<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PinLockController extends Controller
{
    public function showLock()
    {
        return view('pin.lock');
    }

    public function unlock(Request $request)
    {
        $request->validate([
            'pin' => ['required', 'digits:6'],
        ], [
            'pin.digits' => 'PIN harus 6 angka.',
        ]);

        if (! Hash::check($request->input('pin'), $request->user()->pin_code)) {
            return back()->withErrors(['pin' => 'PIN salah. Coba lagi ya.']);
        }

        $request->session()->put('pin_unlocked', true);

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Jurnal berhasil dibuka 🔓');
    }

    public function logoutPin(Request $request)
    {
        $request->session()->forget('pin_unlocked');

        return redirect()->route('pin.lock')
            ->with('info', 'Jurnal telah dikunci kembali. Sampai jumpa 👋');
    }

    public function edit()
    {
        return view('pin.settings', [
            'hasPin' => auth()->user()->hasPin(),
        ]);
    }

    public function update(Request $request)
    {
        $rules = [
            'pin' => ['required', 'digits:6', 'confirmed'],
        ];

        if ($request->user()->hasPin()) {
            $rules['current_pin'] = ['required', 'current_password:web'];
        }

        $validated = $request->validate($rules, [
            'pin.digits' => 'PIN harus 6 angka.',
            'pin.confirmed' => 'Konfirmasi PIN tidak cocok.',
            'current_pin.current_password' => 'Password akun salah.',
        ]);

        $request->user()->update([
            'pin_code' => Hash::make($validated['pin']),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'PIN jurnal berhasil diperbarui 🔐');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password:web'],
        ]);

        $request->user()->update(['pin_code' => null]);
        $request->session()->forget('pin_unlocked');

        return redirect()->route('dashboard')
            ->with('success', 'Kunci PIN telah dinonaktifkan.');
    }
}
