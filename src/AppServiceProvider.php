<?php

namespace Smile\FileManage;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        //单例绑定
        $this->app->singleton('file.manage', function ($app) {
            return new FileManage();
        });

        //合并配置文件
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'file');
    }

    public function provides()
    {
        //延迟加载
        return ['file.manage'];
    }

    public function boot()
    {
        //配置文件
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('file.php'),
        ]);

        //迁移文件
        $this->loadMigrationsFrom(__DIR__.'/../migrations');

        //注册路由
        $this->registerRoutes();
    }

    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });
    }

    protected function routeConfiguration()
    {
        return [
            'prefix'     => config('file.prefix'),
            'middleware' => config('file.middleware'),
        ];
    }
}