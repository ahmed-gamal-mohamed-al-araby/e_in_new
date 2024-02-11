<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        if (Schema::hasTable('notifications')) {
            $notifications = Notification::where('view_status', 0)->where('archive', 0)->orderBy('updated_at', 'DESC')->take(5)->get();
            $notificationsCounter = Notification::where('view_status', 0)->where('archive', 0)->count();
            $notificationsReadCounter = Notification::where('view_status', 1)->where('archive', 0)->count();
        }
        if (isset($notifications)) {
            View::composer('*', function($view) use($notifications, $notificationsCounter, $notificationsReadCounter){
                $view->with('notifications', $notifications);
                $view->with('notificationsCounter', $notificationsCounter);
                $view->with('notificationsReadCounter', $notificationsReadCounter);
            });
        }
    }
}
