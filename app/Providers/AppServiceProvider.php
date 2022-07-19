<?php declare(strict_types=1);

namespace App\Providers;

use App\Models\Job;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('partials.footer', function ($view) {
            $view->with('jobsCount', Job::query()->count());
        });

        DB::listen(function ($query) {
            Log::channel('sqllog')->info($query->sql);
//            dump( $query->sql,
//                $query->bindings,
//                $query->time);
        });
    }
}
