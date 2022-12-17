<?php

namespace App\Providers;

use View;
use DB;
use App\Models\Team;
use App\Policies\TeamPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\CompanySetting;
use Illuminate\Support\Facades\Session;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('auth.login',  function($view){

            $companySettings = CompanySetting::first();
            Session::forget('companySettings');
            Session::push('companySettings', $companySettings); 

            $view->with('companySettings',$companySettings);
            
        });
        $this->registerPolicies();

        //
    }
}
