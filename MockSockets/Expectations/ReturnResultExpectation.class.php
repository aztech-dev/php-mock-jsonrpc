<?php

namespace MockSockets\Expectations
{

    use MockSockets\Response\Response;

    class ReturnResultExpectation extends AbstractExpectation
    {
        private $body;

        public function setBody($body)
        {
            $this->body = $body;
        }
        
        public function getResponse()
        {
            $response = new Response();
            
            $response->setStatus(200);
            $response->setContentType('application/json');
            $response->setId($this->id->getExpectedId());
            $response->setBody($this->body);
            
            return $response;
        }
    }
}