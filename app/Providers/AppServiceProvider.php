<?php

namespace App\Providers;

use App\Repositories\ItemRepositoryInterface;
use App\Repositories\RawSqlItemRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the Raw SQL repository to its interface
        $this->app->bind(ItemRepositoryInterface::class, RawSqlItemRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix for MySQL 1071 byte index limit on utf8mb4
        Schema::defaultStringLength(191);
    }
}