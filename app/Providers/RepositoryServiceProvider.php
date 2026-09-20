<?php

namespace App\Providers;

use App\Repositories\ItemRepositoryInterface;
use App\Repositories\RawSqlItemRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ItemRepositoryInterface::class, RawSqlItemRepository::class);
    }
}