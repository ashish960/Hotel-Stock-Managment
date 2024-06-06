<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        //rate limiter for login

        RateLimiter::for('login', function (Request $request) {
            return [
                Limit::perMinute(500)->response(function (Request $request, array $headers) {
                    return response()->json([
                        'message' => 'Too many requests. Please try again later.',
                    ], Response::HTTP_TOO_MANY_REQUESTS, $headers);
                }),
                Limit::perMinute(10)->by($request->input('email'))->response(function (Request $request, array $headers) {
                    return response()->json([
                        'message' => 'Too many login attempts for this email. Please try again later.',
                    ], Response::HTTP_TOO_MANY_REQUESTS, $headers);
                }),
            ];
        });

        //rate limiter for registration

        RateLimiter::for('register', function (Request $request) {
            return [
                Limit::perMinute(50)->response(function (Request $request, array $headers) {
                    return response()->json([
                        'message' => 'Too many registration attempts. Please try again later.',
                    ], Response::HTTP_TOO_MANY_REQUESTS, $headers);
                }),
                Limit::perMinute(5)->by($request->input('email'))->response(function (Request $request, array $headers) {
                    return response()->json([
                        'message' => 'Too many registration attempts for this email. Please try again later.',
                    ], Response::HTTP_TOO_MANY_REQUESTS, $headers);
                }),
            ];
        });
    }
}
