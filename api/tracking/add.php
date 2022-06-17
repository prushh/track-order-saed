<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../utils.php";

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    bad_request();
}

require_once "../database/connection.php";
require_once '../obj/tracking.php';

$db = new Database();
$conn = $db->openConnection();

$tracking = new Tracking($conn);

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->courier) &&
    !empty($data->status_id)
) {
    $tracking->courier = $data->courier;
    $tracking->status_id = $data->status_id;


    if ($tracking->add()) {
        http_response_code(201);
        echo json_encode(array("message" => "Tracking aggiunto."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Errore di connessione."));
    }
} else {
    bad_request();
}
