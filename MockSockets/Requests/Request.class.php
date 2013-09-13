<?php

namespace MockSockets\Requests
{
    class Request
    {
        private $requestLine;
        
        private $headers;
        
        private $body;
        
        public function __construct(RequestLine $requestLine, array $headers, $body)
        {
            $this->requestLine = $requestLine;
            $this->headers = $headers;
            $this->body = $body;
        }
        
        public function getRequestLine()
        {
            return $this->requestLine;
        }
        
        public function getHeaders()
        {
            return $this->headers;
        }
        
        public function getBody()
        {
            return $this->body;
        }
    }
}