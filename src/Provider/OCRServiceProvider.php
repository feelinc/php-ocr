<?php

namespace Sule\OCR\Provider;

/*
 * This file is part of the Sulaeman OCR package.
 *
 * (c) Sulaeman <me@sulaeman.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Support\ServiceProvider;

use Sule\OCR\OCR;

abstract class OCRServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->setupConfig();

        $this->registerOCRServices();
    }

    /**
     * Setup the configuration.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'sule/ocr');
    }

    /**
     * Register the OCR services.
     *
     * @return void
     */
    protected function registerOCRServices()
    {
        $this->app->singleton(OCR::class, function ($app) {
            $config = $app['config']['sule/ocr'];

            return new OCR($config);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [OCR::class];
    }
}
