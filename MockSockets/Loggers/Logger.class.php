<?php

namespace MockSockets\Loggers
{
    interface Logger
    {
        function log($message, $level = 0);
    }
}