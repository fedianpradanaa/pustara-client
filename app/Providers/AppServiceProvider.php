<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $appName = static function (): string {
            try {
                $name = DB::table('tclient')
                    ->where('client_id', config('app.client_id', ''))
                    ->value('application_name');
            } catch (\Throwable $e) {
                $name = null;
            }

            return trim((string) $name) ?: (string) config('app.name');
        };

        VerifyEmail::toMailUsing(function ($notifiable, $url) use ($appName) {
            $name = $appName();

            return (new MailMessage)
                ->subject('Verifikasi Email Akun ' . $name)
                ->view('emails.action', [
                    'appName' => $name,
                    'greeting' => 'Halo, ' . $notifiable->name . '!',
                    'lines' => [
                        'Terima kasih telah mendaftar di ' . $name . '.',
                        'Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda. Akun baru dapat digunakan setelah email terverifikasi.',
                    ],
                    'actionText' => 'Verifikasi Email',
                    'actionUrl' => $url,
                    'footerLines' => [
                        'Jika Anda tidak merasa mendaftar, abaikan email ini.',
                    ],
                ]);
        });

        ResetPassword::toMailUsing(function ($notifiable, $token) use ($appName) {
            $name = $appName();
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));
            $expire = (int) config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');

            return (new MailMessage)
                ->subject('Atur Ulang Kata Sandi ' . $name)
                ->view('emails.action', [
                    'appName' => $name,
                    'greeting' => 'Halo, ' . $notifiable->name . '!',
                    'lines' => [
                        'Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda.',
                        'Klik tombol di bawah ini untuk membuat kata sandi baru. Tautan berlaku selama ' . $expire . ' menit.',
                    ],
                    'actionText' => 'Atur Ulang Kata Sandi',
                    'actionUrl' => $url,
                    'footerLines' => [
                        'Jika Anda tidak meminta pengaturan ulang kata sandi, abaikan email ini. Kata sandi Anda tidak akan berubah.',
                    ],
                ]);
        });

        View::composer([
            'layouts.app',
            'layouts.auth',
            'formregister',
            'errors.403',
            'readbook',
            'welcome',
            'auth.login',
            'auth.passwords.email',
            'auth.passwords.reset',
            'auth.passwords.success',
            'offlinevisitor',
        ], function ($view) {
            $profile = DB::table('tclient')
                ->select('application_name', 'logo', 'logo_small')
                ->where('client_id', config('app.client_id', ''))
                ->first();

            $resolveLogo = static function (?string $value): string {
                $path = trim((string) $value);
                if ($path === '') {
                    return '';
                }

                if (filter_var($path, FILTER_VALIDATE_URL)) {
                    return in_array(parse_url($path, PHP_URL_SCHEME), ['http', 'https'], true) ? $path : '';
                }

                if (Str::startsWith($path, ['storage/', '/storage/'])) {
                    return url('/' . ltrim($path, '/'));
                }

                return asset('storage/images/logo/' . ltrim($path, '/'));
            };

            $clientDirectory = hash('sha256', config('app.client_id', ''));
            $resolveTenantLogo = static function (string $filename) use ($clientDirectory): string {
                $relativePath = 'storage/images/logo/' . $clientDirectory . '/' . $filename;
                $absolutePath = public_path($relativePath);
                if (!is_file($absolutePath)) {
                    return '';
                }

                return asset($relativePath . '?v=' . filemtime($absolutePath));
            };

            $profileLogoUrl = $resolveLogo($profile->logo ?? null);
            $profileSmallLogoUrl = $resolveLogo($profile->logo_small ?? null);
            $logoUrl = $resolveTenantLogo('logo.png')
                ?: $profileLogoUrl
                ?: asset('storage/images/logo/logo.png');
            $smallLogoUrl = $resolveTenantLogo('logo_small.png')
                ?: $profileSmallLogoUrl
                ?: $profileLogoUrl
                ?: asset('storage/images/logo/logo_small.png');
            $view->with([
                'clientAppName' => trim((string) ($profile->application_name ?? '')),
                'clientLogoUrl' => $logoUrl,
                'clientSmallLogoUrl' => $smallLogoUrl,
                'clientFaviconUrl' => $resolveTenantLogo('favicon.png')
                    ?: asset('storage/images/logo/favicon.png'),
            ]);
        });
    }
}
