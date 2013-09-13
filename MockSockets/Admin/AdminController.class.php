<?php

namespace MockSockets\Admin
{

    use MockSockets\Admin\Client\RemoteHostInfo;
    use MockSockets\Admin\Client\FOpenConnector;

    class AdminController
    {

        private $hostInfo;

        private $connector;

        public function __construct($hostName = '127.0.0.1', $port = 50000)
        {
            $this->hostInfo = new RemoteHostInfo();
            $this->hostInfo->setHost($hostName);
            $this->hostInfo->setPort($port);
            
            $this->connector = new FOpenConnector();
            $this->connector->setHostInfo($this->hostInfo);
        }

        public function expectError($method, $id, $code, $message)
        {
            $params = array('type' => 'error', 'method' => $method, 'id' => $id, 'code' => $code, 'message' => $message);
            
            $this->connector->query('expect', $params, false);
        }
    }
}