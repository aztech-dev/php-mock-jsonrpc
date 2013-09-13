<?php

namespace MockSockets\Requests
{
    class RequestLine
    {

        private $method;

        private $requestURI;

        private $httpVersion;
        
        public function __construct($method, $requestURI, $httpVersion)
        {
            $this->method = $method;
            $this->requestURI = $requestURI;
            $this->httpVersion = $httpVersion;
        }
        
        public function getMethod()
        {
            return $this->method;
        }
        
        public function getRequestURI()
        {
            return $this->requestURI;
        }
        
        public function getHttpVersion()
        {
            return $this->httpVersion;
        }
    }
}