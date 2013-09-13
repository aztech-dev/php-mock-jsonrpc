<?php

class MockSocket
{
    private $port;

    private $sock;

    private $listening;

    public function __construct($port = 50000)
    {
        $this->port = $port;
    }

    private function dumpPotentialError($socket, $context = '')
    {
        $error = socket_last_error($socket);
        if ($error !== 0)
        {
            if (!empty($context))
                echo $context . ' :: ';

            echo 'Socket error : ' . socket_strerror($error) . PHP_EOL;
            socket_clear_error($socket);
        }
    }

    private function getHttpHeaders($response)
    {
        $length = strlen($response);

        $headers = array(
            "HTTP/1.0 200 OK",
            "Connection: close",
            "Content-Length: $length",
            "Content-Type: application/json",
            "Server: PHPCoins-Mock-json-rpc/v1"
        );

        $status = implode("\r\n", $headers);

        return $status;
    }

    private function writeJsonResponse($socket, $response)
    {
        $json = json_encode($response, JSON_FORCE_OBJECT);
        $headers = $this->getHttpHeaders($json);

        $data = $headers . "\n\n\r\n" . $json;

        $this->writeRaw($socket, $data);

        $this->dumpPotentialError($socket, 'Write');
    }

    private function writeRaw($socket, $data)
    {
        $wrote = 0;
        while ($wrote !== false && $wrote < strlen($data))
        {
            $data = substr($data, $wrote);
            $wrote = socket_write($socket, $data);

            echo 'Socket write : ' . PHP_EOL . substr($data, 0, $wrote) . PHP_EOL;

            $this->dumpPotentialError($socket);
        }
    }

    private function readRequest($socket)
    {
        echo 'Socket read : ' . PHP_EOL;

        $received = "";
        $readLen = 1;
        do
        {
            $data = socket_read($socket, $readLen);
            $received .= $data;

            $this->dumpPotentialError($socket, 'Read');
        }
        while ($data != '}');

        echo $received . PHP_EOL;

        return $received;
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
        $this->sock = $this->createSocket(true);

        $this->dumpPotentialError($this->sock, 'Init');

        $this->listening = true;

        while($this->listening)
        {
            if(($writeSocket = socket_accept($this->sock)) !== false)
            {
                socket_set_block($writeSocket);
                $received = $this->readRequest($writeSocket);

                $response = array('id' => 1, 'error' => array('message' => 'random error', 'code' => -1));
                $this->writeJsonResponse($writeSocket, $response);

                socket_close($writeSocket);

                $this->listening = false;
            }
        }

        socket_close($this->sock);
    }
}