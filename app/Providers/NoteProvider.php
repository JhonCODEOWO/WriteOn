<?php

namespace App\Providers;

use App\Services\NoteService;
use Illuminate\Support\ServiceProvider;

class NoteProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(NoteService::class, function() {
            return new NoteService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
