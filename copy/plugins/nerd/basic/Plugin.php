<?php namespace Nerd\Basic;

use System\Classes\PluginBase;
use Event;

class Plugin extends PluginBase
{
    public function boot()
    {
        // Extend all backend form usage
        Event::listen('backend.form.extendFields', function($widget) {
            
            if (
                !$widget->getController() instanceof \RainLab\Pages\Controllers\Index ||
                !$widget->model instanceof \RainLab\Pages\Classes\Page
            ) {
                return;
            }

            $widget->removeField('markup');
        });

    }

    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }


}
