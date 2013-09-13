<?php

namespace MockSockets\Assertions
{
    use MockSockets\JsonRpc\JsonRpcRequest;
				class MethodIdAssertion implements Assertion
    {
        private $expectedId;
        
        public function __construct($expectedId)
        {
            if (!is_int($expectedId))
            {
                throw new \InvalidArgumentException('$expectedId must be an int.');
            }
            
            $this->expectedId = $expectedId;
        }
        
        public function verify(JsonRpcRequest $request)
        {
            return ($request->getId() == $this->expectedId);
        }
    }
}