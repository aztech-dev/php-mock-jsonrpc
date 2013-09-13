<?php

namespace MockSockets\Http
{
    class Header
    {
        private $name;
        private $value;
        
        public function __construct($name, $value = '')
        {
            $this->name = $name;
            $this->value = $value;
        }
        
        public function getName()
        {
            return $this->name;
        }
        
        public function getValue()
        {
            return $this->value;
        }
        
        public function setValue($value)
        {
            $this->value = $value;
        }
        
        public function __toString()
        {
            return sprintf('%s: %s', $this->name, $this->value);
        }
    }
}