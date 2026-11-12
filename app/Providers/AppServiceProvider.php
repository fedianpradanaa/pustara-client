<?php

namespace App\Providers;

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
        View::composer([
            'layouts.app',
            'layouts.auth',
            'formregister',
            'errors.403',
            'readbook',
            'welcome',
            'auth.login',
            'auth.passwords.email',
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
