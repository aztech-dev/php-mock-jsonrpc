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
            $this->id = $id;
        }

        public function setError($errorCode, $errorMessage)
        {
            $this->error = array('code' => $errorCode, 'message' => $errorMessage);
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