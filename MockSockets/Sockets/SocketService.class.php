<?php

namespace MockSockets\Sockets
{
    use MockSockets\Loggers\Logger;
    use MockSockets\Loggers\NullLogger;

    abstract class SocketService
    {

        protected $logger;

        protected $socket;

        public function __construct($socket, Logger $logger = null)
        {
            if (!$this->isSocket($socket))
            {
                throw new \InvalidArgumentException('$socket must a socket ressource.');
            }
            
            $this->socket = $socket;
            $this->logger = $logger ?  : NullLogger::Instance();
        }

        private function isSocket($socket)
        {
            if (!is_resource($socket))
                return false;
            
            return get_resource_type($socket) == 'Socket';
        }

        private $previousError = 0;
        
        protected function dumpPotentialError($context = '')
        {
            $error = socket_last_error($this->socket);
            
            if ($error !== 0 && $error !== 10035)
            {
                $message = '';
                if (!empty($context)) $message .= $context . ' :: ';
                $message .= 'Socket error : ' . socket_strerror($error) . PHP_EOL;
                
                $this->logger->log($message);
            }
            
            socket_clear_error($this->socket);
        }
        
        protected function readRaw()
        {
            $received = "";
            $readLen = 1;
            
            do
            {
                $data = socket_read($this->socket, 1);
                $received .= $data;
                
                $this->dumpPotentialError('SocketService::readRaw()');
            }
            while (strlen($data) > 0);
            
            if (!empty($received))
            {
                $this->logger->log($this->getPeerName() . ': Received data');
                $this->logger->log('');
                foreach (explode("\r\n", trim($received)) as $line)
                {
                    $this->logger->log('> ' . $line);
                }
                $this->logger->log('');
            }
            
            return $received;
        }
        
        protected function writeRaw($data)
        {
            $writtenCharCount = 0;
            $writtenChars = '';
            
            while ($writtenCharCount !== false && $writtenCharCount < strlen($data))
            {
                $data = substr($data, $writtenCharCount);
                $writtenCharCount = socket_write($this->socket, $data);
                $writtenChars .= substr($data, 0, $writtenCharCount);
                
                $this->dumpPotentialError($this->socket);
            }
            
            $this->logger->log($this->getPeerName() . ': Sent data');
            $this->logger->log('');
            foreach (explode("\r\n", trim($writtenChars)) as $line)
            {
                $this->logger->log('> ' . $line);
            }
            $this->logger->log('');
        }
        
        protected function getPeerName()
        {
            $address = '';
            $port = 0;
            
            socket_getpeername($this->socket, $address, $port);
            
            return sprintf('%s:%s', $address, $port);
        }
    }
}