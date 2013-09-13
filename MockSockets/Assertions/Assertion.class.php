<?php

namespace MockSockets\Assertions
{

    use MockSockets\JsonRpc\JsonRpcRequest;

    interface Assertion
    {

        function verify(JsonRpcRequest $request);
    }

}