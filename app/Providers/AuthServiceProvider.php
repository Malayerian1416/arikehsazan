<?php

namespace App\Providers;

use App\Models\MenuAction;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define("adminUser",function ($user){return $user->isAdmin();});
        foreach (MenuAction::all() as $action){
            Gate::define($action->action,function ($user,$model) use ($action){return $user->hasPermission($action->action,$model);});
        }
    }
}
