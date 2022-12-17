<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;
use DB;
use App\Models\Party;
use App\Models\CompanySetting;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;

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
        //




        View::composer('admin.master',  function($view){

            $companySettings = CompanySetting::first();
            Session::forget('companySettings');
            Session::push('companySettings', $companySettings); 

            $view->with('companySettings',$companySettings);
            
        });


        
        View::composer('admin.master',  function($view){

            $companySettings = CompanySetting::first();
            Session::forget('companySettings');
            Session::push('companySettings', $companySettings); 

            $view->with('companySettings',$companySettings);
            
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



        View::composer('admin.includes.sidebar',  function($view){

            $users = DB::table('users')
            ->where('deleted','=', 'No')
            ->where('status','=', 'Active')
            ->get();

            $view->with('users',$users);
            
        });

        View::composer('admin.setups.companySettings.company-settings',  function($view){

            $suppliers = ["There are suppliers"];



            $view->with('suppliers',$suppliers);
            
        });


    }
}
