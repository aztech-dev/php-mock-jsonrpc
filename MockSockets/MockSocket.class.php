<?php

namespace MockSockets
{

    use MockSockets\Loggers\EchoLogger;
    use MockSockets\Sockets\SocketHandler;

    class MockSocket
    {

        private $port;

        private $sock;

        private $listening;

        private $logger;

        public function __construct($port = 50000)
        {
            $this->port = $port;
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
            $socketHandler = new SocketHandler($this->logger);
            
            while ($this->listening)
            {
                if (($clientSocket = socket_accept($this->sock)) !== false)
                {
                    $socketHandler->handle($clientSocket);
                    $this->listening = !$socketHandler->shouldStopListening();
                }
            }
            
            $this->logger->log('Closing listening socket.');
            socket_close($this->sock);
        }
    }
}