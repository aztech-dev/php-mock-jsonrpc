<?php

namespace MockSockets
{

    use MockSockets\JsonRpc\JsonRpcRequest;
    use MockSockets\Expectations\ReturnErrorExpectation;
    use MockSockets\Response\Response;
    use MockSockets\Expectations\ReturnResultExpectation;

    class CommandHandler
    {

        private $close = false;

        /**
         *
         * @var Expectations\Expectation[] $expectations
         */
        private $expectations = array();

        public function shouldCloseAfterRequest()
        {
            return $this->close;
        }

        public function handleAdminCommand(JsonRpcRequest $request)
        {
            if ($request->getMethod() == 'expect')
            {
                $this->registerExpectation($request);
            }
            
            $response = new Response();
            $response->setStatus(200);
            $response->setBody(true);
            $response->setId($request->getId());
            $response->setContentType('application\json');
            
            return $response;
        }

        private function registerExpectation(JsonRpcRequest $request)
        {
            $params = $request->getParams();
            $expectation = null;
            
            if ($params['type'] == 'error')
            {
                $expectation = $this->buildErrorExpectation($request);
            }
            elseif ($params['type'] == 'result')
            {
                $expectation = $this->buildResultExpectation($request);
            }
            
            if ($expectation !== null)
            {
                $this->expectations[] = $expectation;
            }
        }

        private function buildErrorExpectation(JsonRpcRequest $request)
        {
            $params = $request->getParams();
            $expectation = new ReturnErrorExpectation();
            
            $expectation->setMethod($params['method']);
            $expectation->setId($params['id']);
            $expectation->setErrorCode($params['code']);
            $expectation->setErrorMessage($params['message']);
            
            return $expectation;
        }

        private function buildResultExpectation(JsonRpcRequest $request)
        {
            $params = $request->getParams();
            $expectation = new ReturnResultExpectation();
            
            $expectation->setMethod($params['method']);
            $expectation->setId($params['id']);
            $expectation->setBody($params['result']);
            
            return $expectation;
        }

        public function handleMethod(JsonRpcRequest $request)
        {
            $this->close = true;
            
            foreach ($this->expectations as $expectation)
            {
                if ($expectation->matches($request))
                {
                    return $expectation->getResponse();
                }
            }
        }
    
    }
}