<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
    // public function boot()
    // {
    //         Schema::defaultStringLength(191);
    // }
    public function boot()
    {
        // ✅ Fix for mPDF GD namespace issue
        if (!function_exists('Mpdf\\Image\\imagecreatefromstring')) {
            // dynamically create namespaced alias
            eval('namespace Mpdf\\Image; function imagecreatefromstring($data) { return \\imagecreatefromstring($data); }');
        }
    }
}
