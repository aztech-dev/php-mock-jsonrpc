<?php

namespace MockSockets
{

    use MockSockets\Requests\RequestBuilder;
    use MockSockets\Http\Listener;
    use MockSockets\Loggers\EchoLogger;
    use MockSockets\Http\Responder;
    use MockSockets\Expectations\ReturnErrorExpectation;
    use MockSockets\JsonRpc\JsonRpcRequest;

    class MockSocket
    {

        private $port;

        private $sock;

        private $listening;

        private $logger;

        public function __construct($port = 50000)
        {
            $this->port = $port;
            
            $this->requestBuilder = new RequestBuilder();
            $this->logger = new EchoLogger();
        }

        private function createSocket($block = true)
        {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            socket_bind($socket, '127.0.0.1', $this->port);
            socket_listen($socket);
            
            $block ? socket_set_block($socket) : socket_set_nonblock($socket);
            
            return $socket;
        }

        public function listen()
        {
            $this->sock = $this->createSocket(false);
            $this->listening = true;
            
            while ($this->listening)
            {
                if (($writeSocket = socket_accept($this->sock)) !== false)
                {
                    $this->logConnection($writeSocket);
                    
                    socket_set_nonblock($writeSocket);
                    
                    $listener = new Listener($writeSocket, $this->logger);
                    $request = $listener->readRequest();
                    
                    $responder = new Responder($writeSocket, $this->logger);
                    
                    $expectation = new ReturnErrorExpectation();
                    $expectation->setMethod('bla');
                    $expectation->setErrorCode(-1);
                    $expectation->setErrorMessage('random error from expectation');
                    $expectation->setId(1);
                    
                    $jsonRpcRequest = new JsonRpcRequest($request);
                    
                    if ($expectation->matches($jsonRpcRequest))
                    {
                        $response = $expectation->getResponse();
                        $responder->send($response);
                    }
                    
                    $this->logConnectionClosed($writeSocket);
                    socket_close($writeSocket);
                    
                    $this->listening = false;
                }
            }
            
            socket_close($this->sock);
        }

        private function logConnection($socket)
        {
            $this->logger->log(sprintf("%s: Accepted connection", $this->getPeerName($socket)));
        }

        private function logConnectionClosed($socket)
        {
            $this->logger->log(sprintf("%s: Closing connection", $this->getPeerName($socket)));
        }

        private function getPeerName($socket)
        {
            $address = "";
            $port = 0;
            
            socket_getpeername($socket, $address, $port);
            
            return sprintf('%s:%s', $address, $port);
        }
    }
}