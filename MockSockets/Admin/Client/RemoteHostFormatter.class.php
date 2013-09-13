<?php

namespace MockSockets\Admin\Client
{

    interface RemoteHostFormatter
    {

        function format(RemoteHostInfo $hostInfo, $withCredentials = false);
    }
}