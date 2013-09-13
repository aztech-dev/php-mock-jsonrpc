<?php

namespace MockSockets\JsonRpc
{
    use MockSockets\Requests\Request;

    class JsonRpcRequest  
    {
        private $request;
        
        private $id;
        
        private $method;
        
        private $params;
        
        public function __construct(Request $request)
        {
            $this->request = $request;
            
            $jsonBody = json_decode($request->getBody(), false);
            
            $this->method = $jsonBody->method;
            $this->params = $jsonBody->params;
            $this->id = $jsonBody->id;
        }
        
        public function getMethod()
        {
            return $this->method;
        }
        
        public function getParams()
        {
            return $this->params;
        }
        
        public function getId()
        {
            return $this->id;
        }
    }
}