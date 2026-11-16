<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

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
        // FORCER PostgreSQL À L'EXÉCUTION (ignore le cache et le .env)
        Config::set('database.default', 'pgsql');
        
        Config::set('database.connections.pgsql', [
            'driver' => 'pgsql',
            'host' => 'ep-dawn-sun-bafgfqad-pooler.c-8.eu-central-1.aws.neon.tech',
            'port' => '5432',
            'database' => 'neondb',
            'username' => 'neondb_owner',
            'password' => 'npg_P0qTtQcdYuB2',
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'require',
        ]);
    }
}