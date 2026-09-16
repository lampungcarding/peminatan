<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        date_default_timezone_set('Asia/Jakarta');
        config([
            'app.timezone' => 'Asia/Jakarta',
            'app.locale' => 'id',
        ]);
        \Carbon\Carbon::setLocale('id');
        @setlocale(LC_TIME, 'id_ID.utf8', 'id_ID', 'indonesia', 'id');
    }
}
