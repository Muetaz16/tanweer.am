<?php

namespace App\Providers;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
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
        Carbon::setLocale(config('app.locale', 'ar'));

        View::composer('layouts.public', function ($view) {
            $navActiveKey = match (true) {
                request()->routeIs('home') => 'home',
                request()->routeIs('category.show') => request()->route('type'),
                request()->routeIs('posts.show') => Post::query()
                    ->published()
                    ->where('slug', request()->route('slug'))
                    ->value('type'),
                default => null,
            };

            $view->with('navActiveKey', $navActiveKey);
        });
    }
}
