<?php

namespace Sule\OCR\Service;

/*
 * This file is part of the Sulaeman OCR package.
 *
 * (c) Sulaeman <me@sulaeman.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Psr\Log\LoggerInterface;

interface ServiceInterface
{
    /**
     * Set the logger instance.
     *
     * @param  \Psr\Log\LoggerInterface  $logger
     * @return void
     */
    public function setLogger(LoggerInterface $logger);

    /**
     * Sets a option value.
     *
     * @param string $key
     * @param mixed  $value
     * @return self
     */
    public function option($key, $value);

    /**
     * Sets a configuration value.
     *
     * @param string $key
     * @param string $value
     * @return self
     */
    public function config($key, $value);

    /**
     * Executes service command and returns the generated output.
     *
     * @return string
     */
    public function parse();
}
