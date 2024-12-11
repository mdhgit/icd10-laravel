<?php

namespace Mydiabeteshome\ICD10;

use Illuminate\Support\ServiceProvider;

class ICD10ServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->app->singleton(ICD10::class, function () {
            return new ICD10($this);
        });
    }
}
