<?php

namespace MockSockets\Assertions
{

    use MockSockets\JsonRpc\JsonRpcRequest;

    class MethodNameAssertion implements Assertion
    {

        private $expectedMethod;

        public function __construct($name)
        {
            if (!is_string($name))
            {
                throw new \InvalidArgumentException('$name must be a string');
            }
            
            $this->expectedMethod = $name;
        }

        public function verify(JsonRpcRequest $request)
        {
            return ($request->getMethod() == $this->expectedMethod);
        }
    }
}