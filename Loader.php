<?php

require_once __DIR__ . '/SplClassLoader.class.php';

$loader = new SplClassLoader('MockSockets', __DIR__);
$loader->setFileExtension('.class.php');
$loader->register();