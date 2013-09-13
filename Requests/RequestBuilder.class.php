<?php

namespace MockSockets\Requests
{

    class RequestBuilder
    {
        public function buildRequest($requestData)
        {
            $requestLine = $this->parseRequestLine($requestData);
            $headers = $this->parseHeaders($requestData);
            $body = $this->extractRawBody($requestData);
            
            $request = new Request($requestLine, $headers, $body);
            
            return $request;
        }

        private function parseRequestLine($requestData)
        {
            $headerSection = explode("\r\n\r\n", $requestData, 1);
            $rawHeaders = explode("\r\n", $headerSection[0]);
            
            $requestLine = array_slice($rawHeaders, 0, 1);
            $requestLineItems = explode(' ', $requestLine);
            
            if (count($requestLineItems) !== 3)
            {
                throw new \Exception();
            }
            
            $requestLine = new RequestLine($requestLineItems[0], $requestLineItems[1], $requestLineItems[2]);
            
            return $requestLine;
        }

        private function parseHeaders($requestData)
        {
            $headerSection = explode("\r\n\r\n", $requestData, 1);
            $rawHeaders = explode("\r\n", $headerSection[0]);
            
            $headers = array();
            
            foreach (array_slice($rawHeaders, 1) as $rawHeader)
            {
                $header = $this->parseHeader($rawHeader);
                $headers[$header->getName()] = $header;
            }
            
            return $headers;
        }

        private function parseHeader($rawHeader)
        {
            $parts = explode(':', $rawHeader, 1);
            
            return new RequestHeader(trim($parts[0]), trim($parts[1]));
        }
        
        private function extractRawBody($requestData)
        {
            $parts = explode("\r\n\r\n", $requestData, 2);
            
            return $parts[1];
        }
    }

}