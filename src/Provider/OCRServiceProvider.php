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
     * The service classes.
     *
     * @var array
     */
    protected $serviceClasses;

    /**
     * The service instances.
     *
     * @var array
     */
    protected $services;

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

        $this->registerOCR();
        $this->registerServices();
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
     * Register the OCR.
     *
     * @return void
     */
    protected function registerOCR()
    {
        $this->app->singleton(OCR::class, function ($app) {
            $driver  = $app['config']['sule/ocr.driver'];

            $serviceClasses   = $this->getServiceClasses();
            $serviceInstances = $this->getServices();
            $services         = [];
            foreach ($serviceClasses as $index => $class) {
                $services[$index] = $serviceInstances[$class];
            }

            $ocr = new OCR($driver, $services);
            $ocr->setLogger($app['log']);

            return $ocr;
        });
    }

    /**
     * Register the services.
     *
     * @return void
     */
    protected function registerServices()
    {
        foreach ($this->getServices() as $index => $item) {
            $this->app->singleton($index, function () use ($item) {
                return $item;
            });
        }
    }

    /**
     * Return collector instances available.
     *
     * @return array
     * @throws \RuntimeException
     */
    protected function getServices()
    {
        if (is_null($this->services)) {
            $this->services = [];

            foreach ($this->getServiceClasses() as $key => $item) {
                $config = $this->app['config']['sule/ocr.'.$key];

                $this->services[$item] = new $item($config);
                $this->services[$item]->setLogger($this->app['log']);
            }
        }

        return $this->services;
    }

    /**
     * Return service classes available.
     *
     * @return array
     * @throws \RuntimeException
     */
    protected function getServiceClasses()
    {
        if (is_null($this->serviceClasses)) {
            $this->serviceClasses = $this->app['config']['sule/ocr.services'];
        }

        return $this->serviceClasses;
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array_merge(
            [OCR::class], 
            array_values($this->getServiceClasses())
        );
    }
}
