<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\BahanBaku;
use App\Models\Produk;

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
        View::composer('layouts.app', function ($view) {
            $notifBahanMenipis = collect();
            $notifProdukMenipis = collect();
            $totalNotifStok = 0;

            if (auth()->check()) {
                $notifBahanMenipis = BahanBaku::where('stok', '<', 50)->orderBy('stok', 'asc')->get();
                $notifProdukMenipis = Produk::where('stok', '<', 10)->orderBy('stok', 'asc')->get();
                $totalNotifStok = $notifBahanMenipis->count() + $notifProdukMenipis->count();
            }

            $view->with(compact('notifBahanMenipis', 'notifProdukMenipis', 'totalNotifStok'));
        });
    }
}

