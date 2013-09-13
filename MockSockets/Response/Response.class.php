<?php

namespace MockSockets\Response
{

    use MockSockets\Http\Header;

    class Response
    {

        private $status;

        private $headers = array();

        private $id;

        private $error = null;

        private $body = null;

        public function setStatus($status)
        {
            $this->status = $status;
        }

        public function setContentType($contentType)
        {
            $this->headers['Content-Type'] = new Header('Content-Type', $contentType);
        }

        public function setId($id)
        {
            $this->id = intval($id);
        }

        public function setBody($body)
        {
            $this->body = $body;
        }

        public function setError($errorCode, $errorMessage)
        {
            $this->error = array('code' => $errorCode, 'message' => $errorMessage);
        }

        public function getBody()
        {
            return $this->body;
        }

        public function isError()
        {
            return !is_null($this->error);
        }

        public function getError()
        {
            return $this->error;
        }

        public function getId()
        {
            return $this->id;
        }

        public function getStatus()
        {
            return $this->status;
        }

        public function getHeaders()
        {
            return $this->headers;
        }
    }
}