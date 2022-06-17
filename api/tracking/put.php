<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../utils.php";

if ($_SERVER['REQUEST_METHOD'] != 'PUT') {
    bad_request();
}

require_once "../database/connection.php";
require_once '../obj/tracking.php';

$db = new Database();
$conn = $db->openConnection();

$tracking = new Tracking($conn);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id) && !empty($data->courier) && !empty($data->status_id)) {
    $tracking->id = $data->id;
    $tracking->courier = $data->courier;
    $tracking->status_id = $data->status_id;

    if ($tracking->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "Tracking aggiornato con successo."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Tracking inesistente o protetto."));
    }
} else if (!empty($data->id) && !empty($data->status_id)) {
    $tracking->id = $data->id;
    $tracking->status_id = $data->status_id;

    if ($tracking->update_status()) {
        http_response_code(200);
        echo json_encode(array("message" => "Status aggiornato con successo."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Status inesistente o protetto."));
    }
} else {
    bad_request();
}
