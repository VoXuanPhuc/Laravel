<?php

namespace Encoda\Core\Providers;

use Encoda\Core\Http\Requests\FormRequest;
use Illuminate\Support\ServiceProvider;

class FormRequestProvider extends ServiceProvider
{

    public function boot() {

        $this->app->resolving(FormRequest::class, function ($form, $app) {
            $form = FormRequest::createFrom($app['request'], $form);
            $form->setContainer($app);
        });

        $this->app->afterResolving(FormRequest::class, function (FormRequest $form) {
            $form->validate();
        });
    }
}
