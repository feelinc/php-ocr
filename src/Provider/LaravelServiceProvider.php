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

class LaravelServiceProvider extends OCRServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        parent::boot();

        $this->publishes([
            realpath(__DIR__.'/../../config/config.php') => config_path('sule/ocr.php')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        parent::register();
    }
}
