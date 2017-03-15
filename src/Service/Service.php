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

abstract class Service implements ServiceInterface
{
    /**
     * The logger instance.
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * The config.
     *
     * @var array
     */
    protected $config;

    /**
     * Create a new instance.
     *
     * @param  array  $config
     * @return void
     */
    public function __construct(Array $config)
    {
        $this->config = $config;
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
