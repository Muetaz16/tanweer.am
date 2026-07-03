<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminLoginController extends Controller
{
    public function create(): View
    {
        return view('admin.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        // Automatically login the first admin found in the database
        $user = User::where('is_admin', true)->first();

        if (!$user) {
            // Fallback: create a default admin if none exists
            $user = User::create([
                'name' => 'مدير تنوير',
                'email' => 'admin@tanweer.local',
                'password' => bcrypt('123456789'),
                'is_admin' => true,
            ]);
        }

        Auth::login($user, $request->boolean('remember', true));

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
