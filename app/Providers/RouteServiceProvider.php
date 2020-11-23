<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            $this->mapApiRoutes();
//            Route::prefix('api')
//                ->middleware('api')
//                ->namespace($this->namespace)
//                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/log/api.php'));
        Route::prefix('api/authentication')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/authentication/api.php'));
        Route::prefix('api/attendance')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/attendance/api.php'));
        Route::prefix('api/job_board')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/job_board/api.php'));
        Route::prefix('api/cecy')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/cecy/api.php'));
        Route::prefix('api/web')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/web/api.php'));
        Route::prefix('api/ignug')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/ignug/api.php'));
        Route::prefix('api/teacher_eval')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/teacher_eval/api.php'));
        Route::prefix('api/community')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/community/api.php'));
        Route::prefix('api/v9')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v9/api.php'));
        Route::prefix('api/v10')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v10/api.php'));
        Route::prefix('api/v11')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/v11/api.php'));
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60);
        });
    }
}
