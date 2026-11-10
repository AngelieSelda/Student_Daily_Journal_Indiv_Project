<?php

namespace App\Providers;

use App\Models\Journal;
use App\Models\User;
use App\Policies\V1\JournalPolicy;
use App\Policies\V1\UserPolicy;
use Illuminate\Support\Facades\Gate;
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

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Journal::class, JournalPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
    }
}
