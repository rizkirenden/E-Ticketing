<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Traits\LogsActivity; // Tambahkan ini

class LoginController extends Controller
{
    use LogsActivity; // Tambahkan ini

    /**
     * Menampilkan halaman login
     */
    public function index()
    {
        // Jika sudah login, redirect ke dashboard
        if (Auth::check()) {
            return redirect()->intended('dashboard');
        }

        return view('auth.login', [
            'site_key' => env('RECAPTCHA_SITE_KEY'),
            'recaptcha_enabled' => env('RECAPTCHA_ENABLED', true)
        ]);
    }

    /**
     * Proses autentikasi login
     */
    public function authenticate(LoginRequest $request)
    {
        try {
            // Validasi reCAPTCHA jika diaktifkan
            if (env('RECAPTCHA_ENABLED', true)) {
                $recaptchaToken = $request->input('g-recaptcha-response');

                // Log untuk debugging
                Log::info('=== reCAPTCHA DEBUG START ===');
                Log::info('reCAPTCHA token received:', [
                    'exists' => !empty($recaptchaToken),
                    'length' => strlen($recaptchaToken ?? ''),
                    'first_chars' => substr($recaptchaToken ?? '', 0, 20) . '...'
                ]);

                if (!$recaptchaToken) {
                    Log::warning('reCAPTCHA token missing');

                    // Log failed login
                    $this->logFailedLogin($request->username, $request->ip(), 'reCAPTCHA token missing');

                    return back()->withErrors([
                        'username' => 'reCAPTCHA verification failed. Token tidak ditemukan. Silahkan refresh halaman dan coba lagi.'
                    ])->onlyInput('username');
                }

                // Verify reCAPTCHA dengan Google
                $client = new Client([
                    'timeout' => 15,
                    'verify' => false,
                    'http_errors' => false
                ]);

                try {
                    $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                        'form_params' => [
                            'secret' => env('RECAPTCHA_SECRET_KEY'),
                            'response' => $recaptchaToken,
                            'remoteip' => $request->ip()
                        ]
                    ]);

                    $body = json_decode((string) $response->getBody(), true);

                    Log::info('Google reCAPTCHA response:', [
                        'success' => $body['success'] ?? false,
                        'score' => $body['score'] ?? 0,
                        'action' => $body['action'] ?? '',
                        'error_codes' => $body['error-codes'] ?? []
                    ]);

                    // Cek apakah response sukses
                    if (!isset($body['success']) || $body['success'] !== true) {
                        $errorCodes = isset($body['error-codes']) ? implode(', ', $body['error-codes']) : 'Unknown error';

                        Log::warning('reCAPTCHA failed - Google returned error:', [
                            'error_codes' => $errorCodes
                        ]);

                        // Log failed login
                        $this->logFailedLogin($request->username, $request->ip(), "reCAPTCHA failed: {$errorCodes}");

                        $errorMessage = 'reCAPTCHA verification failed.';
                        return back()->withErrors(['username' => $errorMessage])->onlyInput('username');
                    }

                    // Cek action harus 'login'
                    if (($body['action'] ?? '') !== 'login') {
                        Log::warning('reCAPTCHA action mismatch');

                        // Log failed login
                        $this->logFailedLogin($request->username, $request->ip(), 'reCAPTCHA action mismatch');

                        return back()->withErrors([
                            'username' => 'reCAPTCHA verification failed. Invalid action.'
                        ])->onlyInput('username');
                    }

                    // Cek score (minimal 0.5 untuk keamanan)
                    $score = $body['score'] ?? 0;
                    if ($score < 0.5) {
                        Log::warning('reCAPTCHA score too low', ['score' => $score]);

                        // Log failed login
                        $this->logFailedLogin($request->username, $request->ip(), "reCAPTCHA score too low: {$score}");

                        return back()->withErrors([
                            'username' => 'reCAPTCHA verification failed. Skor keamanan terlalu rendah (' . $score . ').'
                        ])->onlyInput('username');
                    }

                } catch (RequestException $e) {
                    Log::error('reCAPTHTTP request failed: ' . $e->getMessage());

                    // Log failed login
                    $this->logFailedLogin($request->username, $request->ip(), 'reCAPTCHA HTTP request failed');

                    return back()->withErrors([
                        'username' => 'Tidak dapat memverifikasi reCAPTCHA. Silahkan coba lagi.'
                    ])->onlyInput('username');
                }

                Log::info('=== reCAPTCHA DEBUG END ===');
            }

            // Rate limiting berdasarkan IP dan username
            $key = 'login:' . $request->ip() . ':' . $request->username;

            if (RateLimiter::tooManyAttempts($key, 5)) {
                $seconds = RateLimiter::availableIn($key);
                $minutes = ceil($seconds / 60);

                Log::warning('Too many login attempts', [
                    'username' => $request->username,
                    'ip' => $request->ip()
                ]);

                // Log failed login
                $this->logFailedLogin($request->username, $request->ip(), 'Too many login attempts');

                return back()->withErrors([
                    'username' => 'Too many login attempts. Please try again in ' . $minutes . ' minute' . ($minutes > 1 ? 's' : '') . '.'
                ])->onlyInput('username');
            }

            // Log login attempt
            Log::info('Login attempt', [
                'username' => $request->username,
                'ip' => $request->ip()
            ]);

            // Cek user exist
            $user = User::where('username', $request->username)->first();

            if (!$user) {
                RateLimiter::hit($key, 900); // 15 menit

                Log::warning('Login failed - user not found', [
                    'username' => $request->username,
                    'ip' => $request->ip()
                ]);

                // Log failed login
                $this->logFailedLogin($request->username, $request->ip(), 'User not found');

                return back()->withErrors([
                    'username' => 'The provided credentials do not match our records.'
                ])->onlyInput('username');
            }

            // Cek password
            if (!Hash::check($request->password, $user->password)) {
                RateLimiter::hit($key, 900);

                Log::warning('Login failed - wrong password', [
                    'username' => $request->username,
                    'user_id' => $user->id,
                    'ip' => $request->ip()
                ]);

                // Log failed login
                $this->logFailedLogin($request->username, $request->ip(), 'Wrong password');

                return back()->withErrors([
                    'username' => 'The provided credentials do not match our records.'
                ])->onlyInput('username');
            }

            // Cek status user
            if (isset($user->is_active) && !$user->is_active) {
                Log::warning('Login failed - inactive user', [
                    'username' => $request->username,
                    'user_id' => $user->id,
                    'ip' => $request->ip()
                ]);

                // Log failed login
                $this->logFailedLogin($request->username, $request->ip(), 'Inactive user');

                return back()->withErrors([
                    'username' => 'Your account is inactive. Please contact administrator.'
                ])->onlyInput('username');
            }

            // Regenerate session ID untuk mencegah session fixation
            $request->session()->regenerate(true);

            // Login user
            Auth::login($user, $request->filled('remember'));

            // Clear rate limiter
            RateLimiter::clear($key);

            // Set last activity untuk session timeout
            session(['last_activity' => time()]);
            session(['login_time' => time()]);

            // Log successful login
            $this->logLogin($user->id, $user->username, $request->ip());

            // Redirect dengan session message
            $redirectTo = $request->session()->pull('url.intended', 'dashboard');

            return redirect()->to($redirectTo)->with('success', 'Welcome back, ' . ($user->nama ?? $user->name ?? 'User') . '!');

        } catch (\Exception $e) {
            Log::error('=== LOGIN CRITICAL ERROR ===');
            Log::error('Login error: ' . $e->getMessage());

            // Log failed login
            $this->logFailedLogin($request->username ?? 'unknown', $request->ip() ?? 'unknown', 'System error: ' . $e->getMessage());

            return back()->withErrors([
                'username' => 'An error occurred during login. Please try again later.'
            ])->onlyInput('username');
        }
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::id();
        $username = $user->username ?? 'unknown';
        $nama = $user->nama ?? $user->name ?? 'unknown';

        // Log sebelum logout
        Log::info('User logging out', [
            'user_id' => $userId,
            'username' => $username,
            'nama' => $nama,
            'ip' => $request->ip()
        ]);

        // Log logout activity
        $this->logLogout($userId, $username, $request->ip());

        // Logout
        Auth::logout();

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Clear any specific session data
        $request->session()->flush();

        return redirect()->route('login')->with('success', 'You have been successfully logged out.');
    }

    /**
     * Menampilkan form forgot password
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send reset password link
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        // Implementasi forgot password di sini
        // Bisa menggunakan Password Broker Laravel

        return back()->with('success', 'Password reset link has been sent to your email.');
    }

    /**
     * Cek status login (untuk AJAX)
     */
    public function checkStatus(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            return response()->json([
                'logged_in' => true,
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'nama' => $user->nama ?? $user->name,
                    'last_activity' => session('last_activity')
                ]
            ]);
        }

        return response()->json(['logged_in' => false], 401);
    }

    /**
     * Refresh CSRF token (untuk AJAX)
     */
    public function refreshCsrf()
    {
        return response()->json([
            'csrf_token' => csrf_token()
        ]);
    }
}
