<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View; 
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

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
        
    Paginator::useBootstrapFive();
          View::composer('*', function ($view) {

        if (!Auth::check()) return;

        $user   = Auth::user();
        $userId = $user->id;
        $roleId = $user->id_role;

        $menus = DB::table('menu as m')
            ->join('role_menu as rm', 'rm.id_menu', '=', 'm.id_menu')
            ->where('m.is_active', 1)
            ->where(function($q) use ($userId, $roleId) {
                $q->where('rm.id_user', $userId)
                  ->orWhere('rm.id_role', $roleId);
            })
            ->orderBy('m.parent_id')
            ->orderBy('m.urutan')
            ->select('m.*')
            ->distinct()
            ->get()
            ->groupBy('parent_id');

        $view->with('menus', $menus);
    });
    }
}
