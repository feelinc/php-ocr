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

interface ProviderInterface
{
    /**
     * Set the logger instance.
     *
     * @param  \Psr\Log\LoggerInterface  $logger
     * @return void
     */
    public function setLogger(LoggerInterface $logger);
}
