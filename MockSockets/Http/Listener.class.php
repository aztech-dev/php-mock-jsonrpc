<?php

namespace MockSockets\Http
{

    use MockSockets\Loggers\Logger;
    use MockSockets\LoggersNullLogger;
    use MockSockets\Sockets\SocketService;
    use MockSockets\Requests\RequestBuilder;

    class Listener extends SocketService
    {
        private $requestBuilder;

        public function __construct($socket, Logger $logger = null)
        {
            parent::__construct($socket, $logger);
            
            $this->requestBuilder = new RequestBuilder();
        }

        public function readRequest()
        {
            do
            {
                $requestData = $this->readRaw();
                usleep(100);
            } while (empty($requestData));
            
            return $this->requestBuilder->buildRequest($requestData);
        }
    }
}