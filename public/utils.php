<?php

//* API PATH *//
$ROOT_API = "http://api-track-order.local/";

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


// ** CURL ** //
function curl_api($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}