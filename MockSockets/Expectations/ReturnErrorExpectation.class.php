<?php

namespace MockSockets\Expectations
{

    use MockSockets\Requests\Request;
    use MockSockets\Assertions\MethodNameAssertion;
    use MockSockets\Assertions\MethodIdAssertion;
    use MockSockets\JsonRpc\JsonRpcRequest;
    use MockSockets\Response\Response;

    class ReturnErrorExpectation implements Expectation
    {

        private $method;

        private $id;

        private $params;

        private $errorCode;

        private $errorMessage;

        public function setId($expectedId = 1)
        {
            $this->id = new MethodIdAssertion($expectedId);
        }

        public function setMethod($methodName)
        {
            $this->method = new MethodNameAssertion($methodName);
        }

        public function setParams($args = null)
        {
            $this->params = array();
        }

        public function setErrorCode($code)
        {
            $this->errorCode = $code;
        }

        public function setErrorMessage($message)
        {
            $this->errorMessage = $message;
        }

        public function matches(JsonRpcRequest $request)
        {
            if (!$this->id->verify($request))
                return false;
            
            if (!$this->method->verify($request))
                return false;
            
            return true;
        }

        public function getResponse()
        {
            $response = new Response();
            
            $response->setStatus(200);
            $response->setContentType('application/json');
            $response->setId($this->id);
            $response->setError($this->errorCode, $this->errorMessage);
            
            return $response;
        }
    }
}