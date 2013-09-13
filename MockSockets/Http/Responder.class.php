<?php

namespace MockSockets\Http
{

    use MockSockets\Sockets\SocketService;
    use MockSockets\Loggers\Logger;
    use MockSockets\Response\Response;

    class Responder extends SocketService
    {

        public function __construct($socket, Logger $logger)
        {
            parent::__construct($socket, $logger);
        }

        private function getHttpHeaders(Response $response)
        {
            $headers = array("HTTP/1.0 200 OK", "Connection: close", 
                "Server: php-mock-jsonrpc/v0.1");
            
            return $headers;
        }

        private function writeJsonResponse(Response $response)
        {
            $body['id'] = $response->getId();
            $body['result'] = ($response->isError()) ? null : $response->getBody();
            $body['error'] =  ($response->isError()) ? $response->getError() : null;
            
            $json = json_encode($body, JSON_FORCE_OBJECT);
            $headers = $this->getHttpHeaders($response);
            
            $responseHeaders = array();
            $responseHeaders[] = (string) new Header('Content-Length', strlen($json));
            foreach ($response->getHeaders() as $header)
            {
                $responseHeaders[] = (string) $header;
            }
            
            $headers = implode("\r\n", array_merge($headers, $responseHeaders));
            $data = $headers . "\r\n\r\n" . $json;            
            
            $this->writeRaw($data);
        }

        public function send(Response $response)
        {
            $this->writeJsonResponse($response);
        }
    }
}