<?php

namespace App\Providers;

use App\Product;
use Illuminate\Support\Facades\Schema;
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
        //Schema::defaultStringLenght(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
                Product::updated(function($product) {
                    if ($product->quantity == 0 && $product->esta_Disponivel()) {
                        $product->status = Product::PRODUTO_NAO_DISPONIVEL;
                        $product->save();
                    }
                });
    }
}
