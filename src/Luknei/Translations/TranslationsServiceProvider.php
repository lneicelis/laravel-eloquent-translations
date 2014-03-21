<?php namespace Luknei\Translations;

use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\Translator;

class TranslationsServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerLoader();

        $this->registerTranslator();

        $this->app['command.translations.load'] = $this->app->share(function($app)
        {
            return new LoaderCommand();
        });

        $this->commands('command.translations.load');

        include_once __DIR__.'/../../routes.php';
    }

    public function boot()
    {
        $this->package('luknei/translations', 'trans');

        include_once __DIR__.'/../../menu.php';
    }

    protected function registerTranslator()
    {
        $this->app->bindShared('translator', function($app)
        {
            $loader = $app['translation.loader'];

            // When registering the translator component, we'll need to set the default
            // locale as well as the fallback locale. So, we'll grab the application
            // configuration so we can easily get both of these values from there.
            $locale = $app['config']['app.locale'];

            $trans = new Translator($loader, $locale);

            $trans->setFallback($app['config']['app.fallback_locale']);

            return $trans;
        });
    }

    /**
     * Register the translation line loader.
     *
     * @return void
     */
    protected function registerLoader()
    {
        $this->app->bindShared('translation.loader', function($app)
        {
            return new EloquentLoader();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('translator', 'translation.loader');
    }

}