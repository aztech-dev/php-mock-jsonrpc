<?php

namespace MockSockets\Expectations
{

    use MockSockets\JsonRpc\JsonRpcRequest;
    use MockSockets\Assertions\MethodIdAssertion;
    use MockSockets\Assertions\MethodNameAssertion;

    abstract class AbstractExpectation implements Expectation
    {

        protected $method;

        protected $id;

        protected $params;

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

        public function matches(JsonRpcRequest $request)
        {
            if (!$this->id->verify($request))
                return false;
            
            if (!$this->method->verify($request))
                return false;
            
            return true;
        }

        public abstract function getResponse();
    }
}