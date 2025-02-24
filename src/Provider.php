<?php

namespace Sule\OCR;

/*
 * This file is part of the Sulaeman OCR package.
 *
 * (c) Sulaeman <me@sulaeman.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Psr\Log\LoggerInterface;

abstract class Provider implements ProviderInterface
{
    /**
     * The logger instance.
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * The driver.
     *
     * @var string
     */
    protected $driver;

    /**
     * The service instances.
     *
     * @var array
     */
    protected $services;

    /**
     * Create a new provider.
     *
     * @param  string  $driver
     * @param  array   $services
     * @return void
     */
    public function __construct($driver, Array $services)
    {
        $this->driver   = $driver;
        $this->services = $services;
    }

    /**
     * {@inheritdoc}
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Write a log.
     *
     * @param  string  $level
     * @param  string  $str
     * @return void
     */
    protected function writeLog($level, $str)
    {
        if ( ! is_null($this->logger)) {
            $this->logger->log($level, $str);
        }
    }
}
