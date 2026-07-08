<?php

namespace App\Providers;

use App\Models\CompanySetting;
use DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::defaultView('pagination::tabler');

        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        View::composer('admin.master', function ($view) {

            $companySettings = CompanySetting::first();
            Session::forget('companySettings');
            Session::push('companySettings', $companySettings);

            $view->with('companySettings', $companySettings);

        });

        View::composer('admin.master', function ($view) {

            $companySettings = CompanySetting::first();
            Session::forget('companySettings');
            Session::push('companySettings', $companySettings);

            $view->with('companySettings', $companySettings);

        });
        /*View::composer('auth.login',  function($view){

            $products = DB::table('products')
            ->join('product_hot_offers','product_hot_offers.tbl_productId','=','products.id')
            ->select('products.*','product_hot_offers.offerPrice')
            ->where('products.availability','!=','off')
            ->where('products.deleted','=','No')
            ->get();
            $view->with('products',$products);

        });*/

        View::composer('admin.includes.sidebar', function ($view) {

            $users = DB::table('users')
                ->where('deleted', '=', 'No')
                ->where('status', '=', 'Active')
                ->get();

            $view->with('users', $users);

        });

        View::composer('admin.setups.companySettings.company-settings', function ($view) {

            $suppliers = ['There are suppliers'];

            $view->with('suppliers', $suppliers);

        });

    }
}
