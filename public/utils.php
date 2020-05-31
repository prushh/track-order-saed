<?php

// ** DEBUGGING ** //
function _debug_log($var, $die = true)
{
    print('<pre>' . print_r($var, true) . '</pre>');
    if ($die)
        die;
}

function _debug_echo($var, $die = true)
{
    echo $var . PHP_EOL;
    if ($die)
        die;
}

function _debug_dump($var, $die = true)
{
    var_dump($var);
    if ($die)
        die;
}
