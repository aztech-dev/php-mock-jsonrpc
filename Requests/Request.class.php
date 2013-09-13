<?php

namespace MockSockets\Requests
{

    class Request
    {
        private $requestLine;
        
        private $headers;
        
        private $id;
        
        private $method;
        
        private $params;
        
        public function __construct(RequestLine $requestLine, array $headers, $body)
        {
            $this->requestLine = $requestLine;
            $this->headers = $headers;
            
            $this->parseBody($body);
        }
        
        private function parseBody($body)
        {
            $jsonBody = json_decode($body, false);
            
            $this->id = $json->id;
            $this->method = $json->method;
            $this->params = $json->params;
        }
        
        public function getRequestLine()
        {
            return $this->requestLine;
        }
        
        public function getHeaders()
        {
            return $this->headers;
        }
        
        public function getId()
        {
            return $this->id;
        }
        
        public function getMethod()
        {
            return $this->method;
        }
        
        public function getParams()
        {
            return $this->params;
        }
    }

}