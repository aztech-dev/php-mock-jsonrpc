<?php

namespace MockSockets\Admin\Client
{

    class FOpenConnector
    {

        protected $id;

        protected $info;

        protected $formatter;

        public function __construct(RemoteHostFormatter $formatter = null)
        {
            $this->formatter = $formatter ?  : new URLFormatter();
            $this->id = -1;
        }

        public function setHostInfo(RemoteHostInfo $info)
        {
            $this->info = $info;
        }

        public function query($method, array $params, $deleteIndexes = true)
        {
            // Check parameters
            if ($deleteIndexes)
            {
                $params = array_values($params);
            }
            
            $currentId = $this->id;
            
            $context = $this->buildRequestContext($method, $params, $currentId);
            $response = $this->performCall($context);
            
            return $this->handleResponse($response, $currentId);
        }

        private function buildRequestContext($method, $params, $currentId)
        {
            // Prepares the request
            $request = array('method' => $method, 'params' => $params, 'id' => $currentId);
            
            $request = json_encode($request);
            
            // performs the HTTP POST
            $opts = array(
                'http' => array('method' => 'POST', 'header' => 'Content-type: application/json', 'content' => $request, 
                    'ignore_errors' => true));
            
            return stream_context_create($opts);
        }

        private function performCall($context)
        {
            if ($fp = fopen($this->formatter->format($this->info, true), 'r', false, $context))
            {
                $response = '';
                
                while ($row = fgets($fp) !== false)
                {
                    $response .= trim($row) . "\n";
                }
                                
                return json_decode($response, true);
            }
            else
            {
                throw new \Exception('Unable to connect to ' . $this->formatter->format($this->info));
            }
        }

        private function handleResponse($response, $id)
        {
            $this->validateResponse($response, $id);
            return $response['result'];
        }

        private function validateResponse($response, $id)
        {
            if ($response['id'] != $id)
            {
                throw new \Exception(
                    sprintf('Incorrect response id (request id: %s, response id: %s)', $id, $response['id']));
            }
            
            if (!is_null($response['error']))
            {
                throw new \Exception(
                    sprintf('Request error %d : %s', $response['error']['code'], $response['error']['message']), 
                    $response['error']['code']);
            }
        }
    }

}