<?php

namespace MockSockets\Expectations
{

    use MockSockets\Requests\Request;
    use MockSockets\JsonRpc\JsonRpcRequest;

    interface Expectation
    {

        function setMethod($name);

        function setParams($args = null);

        function setId($id = 1);

        function matches(JsonRpcRequest $request);

        function getResponse();
    }

}