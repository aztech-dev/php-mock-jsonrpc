<?php

namespace MockSockets\Loggers
{
    class NullLogger implements Logger
    {
        public static function Instance()
        {
            return new NullLogger();
        }
        
        private function __construct()
        {
            
        }
        
        function log($message, $level = 0)
        {
            
        }
    }
}