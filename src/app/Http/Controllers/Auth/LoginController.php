<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\AdminAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user instanceof AdminAccount && $user->isAdminDusun()) {
                return redirect()->route('admin-dusun.dashboard');
            }

            if ($user instanceof AdminAccount && $user->isSuperAdmin()) {
                return redirect()->route('super-admin.dashboard');
            }
        }

        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $username = trim($request->input('username'));
        $throttleKey = Str::transliterate(Str::lower($username).'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()
                ->withInput($request->only('username'))
                ->withErrors([
                    'username' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam '.$seconds.' detik.',
                ]);
        }

        $credentials = [
            'username' => $username,
            'password' => $request->input('password'),
            'removed_at' => null,
        ];

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user instanceof AdminAccount && $user->isAdminDusun()) {
                return redirect()->intended(route('admin-dusun.dashboard'));
            }

            if ($user instanceof AdminAccount && $user->isSuperAdmin()) {
                return redirect()->intended(route('super-admin.dashboard'));
            }

            return redirect()->intended(route('admin.login'));
        }

        RateLimiter::hit($throttleKey, 60);

        return back()
            ->withInput($request->only('username'))
            ->withErrors([
                'username' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
            ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
