<?php namespace BehatApp;

use Illuminate\Support\ServiceProvider;

class BehatAppServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bind('BehatTest', function()
        {
            return new BehatTestsController();
        });
    }

}