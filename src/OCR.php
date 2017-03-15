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

class OCR extends Provider
{
    /**
     * Return a service or default instance.
     *
     * @param  string  $service
     * @return \Sule\OCR\Service\ServiceInterface
     */
    public function getService($service = '')
    {
        if ( ! empty($service) && isset($this->services[$service])) {
            return $this->services[$service];
        }
        
        return $this->services[$this->driver];
    }

    /**
     * {@inheritdoc}
     */
    public function parse($filePath)
    {
        return $this->getService()->source($filePath)->parse();
    }
}
