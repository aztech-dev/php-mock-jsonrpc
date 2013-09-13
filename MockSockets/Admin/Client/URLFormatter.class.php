<?php

namespace MockSockets\Admin\Client
{
    /**
     * Formats remote host info into URL strings.
     *
     * @author fatbeard
     *        
     */
    class URLFormatter implements RemoteHostFormatter
    {

        protected $scheme;

        protected $defaultPort;

        public function __construct($scheme = 'http', $defaultPort = -1)
        {
            $this->scheme = $scheme;
            $this->defaultPort = $defaultPort;
        }

        public function format(RemoteHostInfo $hostInfo, $withCredentials = false)
        {
            if ($hostInfo->hasCredentials() && $withCredentials)
            {
                return sprintf("%s://%s:%s@%s:%s/", $this->scheme, $hostInfo->getUsername(), $hostInfo->getPassword(), 
                    $hostInfo->getHost(), $hostInfo->getPort($this->defaultPort));
            }
            else
            {
                return sprintf("%s://%s:%s/", $this->scheme, $hostInfo->getHost(), 
                    $hostInfo->getPort($this->defaultPort));
            }
        }
    }
}