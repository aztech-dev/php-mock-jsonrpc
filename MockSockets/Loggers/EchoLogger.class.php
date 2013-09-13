<?php

namespace MockSockets\Loggers
{
    class EchoLogger implements Logger
    {
        function log($message, $level = 0)
        {
            echo $message . PHP_EOL;
        }
    }
}