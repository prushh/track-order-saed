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


// ** CURL ** //
// Method: POST, PUT, GET etc
// Data: array("param" => "value") ==> index.php?param=value
function curl_api($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
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

// Decidere se utilizzare curl_api o queste
function curl_get($url)
{
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url
    ]);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function curl_post($url, $array_data)
{
    $additional_headers = array(
        'Accept: application/json',
        'Content-Type: application/json'
    );
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($array_data),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $additional_headers
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response);
}
