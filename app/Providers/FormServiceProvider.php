<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Form;
use Html;

class FormServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        // Register the form components
        Form::component('bsText', 'components.form.text', ['name', 'title', 'value' => NULL, 'attributes' => [], 'required' => false]);        
        Form::component('bsPassword', 'components.form.password', ['name', 'title', 'value' => NULL, 'attributes' => [], 'required' => false]);
        Form::component('bsSelect', 'components.form.select', ['name', 'title', 'values', 'current_value' => NULL, 'attributes' => [], 'required' => false]);
        Form::component('bsPageBar', 'components.common.page-bar', ['name' => '']);

        Form::macro('rawLabel', function($name, $value = null, $options = array()) {
            $label = Form::label($name, $value, $options);

            return Html::decode($label, $value);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        //
    }

}
