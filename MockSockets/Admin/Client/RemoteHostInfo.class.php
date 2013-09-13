<?php

namespace MockSockets\Admin\Client
{

    /**
     * Describes connection information to connect to remote hosts.
     *
     * @author fatbeard
     *        
     */
    class RemoteHostInfo
    {

        /**
         * Name of the remote host
         *
         * @var string $hostName
         */
        private $hostName = '';

        /**
         * Port of the remote host.
         * Default is -1
         *
         * @var unknown $port
         */
        private $port = -1;

        /**
         * Username to connect, if required.
         *
         * @var string $userName
         */
        private $userName = '';

        /**
         * Password, if required;
         *
         * @var string $password
         */
        private $password = '';

        /**
         * Returns the hostname.
         *
         * @return string
         */
        public function getHost()
        {
            return $this->hostName;
        }

        /**
         * Sets the remote hostname
         *
         * @param string $hostName            
         */
        public function setHost($hostName)
        {
            $this->hostName = $hostName;
        }

        /**
         * Returns the remote port.
         *
         * @return number
         */
        public function getPort($defaultPort = -1)
        {
            $port = $this->port;
            
            if ($defaultPort > 0 && $this->isDefaultPort())
            {
                $port = $defaultPort;
            }
            
            if (!$this->isValidPort($port))
            {
                throw new \Exception();
            }
            
            return $port;
        }

        /**
         * Checks whether the port is set or if the default port should be used.
         *
         * @return boolean true if the default port should be, false otherwise.
         */
        public function isDefaultPort()
        {
            return ($this->port == -1);
        }

        /**
         * Checks whether a port is in the valid port range
         * 
         * @param int $port            
         * @return boolean
         */
        private function isValidPort($port)
        {
            // TODO: Rewrite condition
            return !(!$port || empty($port) || !is_numeric($port) || $port < 1 || $port > 65535 ||
                 floatval($port) != intval($port));
        }

        /**
         * Sets the remote port.
         *
         * @param number $port
         *            port to use, -1 if default port should be used [default = -1]
         */
        public function setPort($port = -1)
        {
            $this->port = $port;
        }

        /**
         * Checks whether credentials are set.
         *
         * @return boolean
         */
        public function hasCredentials()
        {
            return !empty($this->userName);
        }

        /**
         * Returns the password
         *
         * @return string
         */
        public function getPassword()
        {
            return $this->password;
        }

        /**
         * Returns the username
         *
         * @return string
         */
        public function getUsername()
        {
            return $this->userName;
        }

        /**
         * Sets the credentials to connect to the remote host.
         * Credentials can be empty to clear credentials.
         *
         * @param string $userName            
         * @param string $password            
         */
        public function setCredentials($userName = '', $password = '')
        {
            $this->userName = $userName;
            $this->password = $password;
        }
    
    }
}