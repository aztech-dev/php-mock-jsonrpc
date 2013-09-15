<?php

namespace MockSockets\Sockets
{

    use MockSockets\Expectations\ReturnErrorExpectation;
    use MockSockets\Http\Listener;
    use MockSockets\Http\Responder;
    use MockSockets\JsonRpc\JsonRpcRequest;
    use MockSockets\Loggers\Logger;
    use MockSockets\CommandHandler;

    class SocketHandler
    {

        private $logger;

        private $commandHandler;

        public function __construct(Logger $logger)
        {
            $this->logger = $logger;
            $this->commandHandler = new CommandHandler();
        }

        public function shouldStopListening()
        {
            return $this->commandHandler->shouldCloseAfterRequest();
        }

        protected function isAdminRequest(JsonRpcRequest $request)
        {
            return $request->getId() == -1;
        }

        protected function handleAdminMethod(JsonRpcRequest $request)
        {
            return $this->commandHandler->handleAdminCommand($request);
        }

        protected function handleMethod(JsonRpcRequest $request)
        {
            return $this->commandHandler->handleMethod($request);
        }
        
        protected function handleRequest($socket, JsonRpcRequest $request)
        {
            if ($this->isAdminRequest($request))
            {
                $response = $this->handleAdminMethod($request);
            }
            else
            {
                $response = $this->handleMethod($request);
            }
            
            $responder = new Responder($socket, $this->logger);
            
            if ($response != null)
            {
                $responder->send($response);
            }
        }

        public function handle($socket)
        {
            $this->logConnection($socket);
            socket_set_nonblock($socket);
            
            $listener = new Listener($socket, $this->logger);
            $request = $listener->readRequest();
            
            $jsonRpcRequest = new JsonRpcRequest($request);
            $this->handleRequest($socket, $jsonRpcRequest);
            
            $this->logConnectionClosed($socket);
            socket_close($socket);
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