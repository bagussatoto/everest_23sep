<?php

$argc = $_SERVER['argc'];
$argv = $_SERVER['argv'];

if ($argc > 1 && isset($argv[1])) {
    $_SERVER['PATH_INFO']   = $argv[1];
    $_SERVER['REQUEST_URI'] = $argv[1];
} else {
    $_SERVER['PATH_INFO']   = '/crons/index';
    $_SERVER['REQUEST_URI'] = '/crons/index';
}

set_time_limit(0);

require_once('index.php');

?>